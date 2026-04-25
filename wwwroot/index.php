<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Rakit\Validation\Validator;
use Sunspikes\Ratelimit\Cache\Adapter\DesarrollaCacheAdapter;
use Sunspikes\Ratelimit\Cache\Factory\DesarrollaCacheFactory;
use Sunspikes\Ratelimit\Throttle\Settings\ElasticWindowSettings;
use Sunspikes\Ratelimit\RateLimiter;
use Sunspikes\Ratelimit\Throttle\Factory\ThrottlerFactory;
use Sunspikes\Ratelimit\Throttle\Hydrator\HydratorFactory;
use Michelf\MarkdownExtra;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use GuzzleHttp\Client;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../validation.php';
require_once __DIR__ . "/../src/Lime/App.php";

$app = new App\Lime\App();

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, ['debug' => IS_DEBUG]);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Berlin');
$twig->addExtension(new MarkdownExtension());
$twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
  public function load($class)
  {
    if (MarkdownRuntime::class === $class) {
      return new MarkdownRuntime(new DefaultMarkdown());
    }
  }
});

$app->service("client", function () {
  $client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'http://127.0.0.1:5001/',
    // You can set any number of default request options.
    'timeout'  => 5.0,
    'headers' => [
      'Authorization' => 'Bearer ' . ACCESS_TOKEN,
      'Accept' => 'application/json',
    ],
  ]);
  return $client;
});

$app->service("markdown", function () {
  $parser = new MarkdownExtra;
  return $parser;
});

$app->service("twig", function () use ($twig) {
  return $twig;
});

$app->get('/', function () {
  requireLogin($this);

  $this->reroute('/trips');
});

$app->get('/login', function () {
  return $this->twig->render('login.html.twig');
});

$app->post('/login', function () {
  $email = $_POST['email'] ?? null;
  $password = $_POST['password'] ?? null;

  try {
    $loginResponse = $this->client->post('/api/auth/login', [
      'json' => [
        'email' => $email,
        'password' => $password,
      ],
    ]);

    $body = json_decode((string) $loginResponse->getBody(), true);

    $_SESSION['token'] = $body['access_token'];

    $this->reroute('/trips');
  } catch (\Exception $e) {
    return $this->twig->render('login.html.twig', [
      'error' => 'Login fehlgeschlagen',
    ]);
  }
});

$app->get("/trips", function () {
  requireLogin($this);

  $response = $this->client->request('GET', '/api/trips', [
    'headers' => authHeaders(),
  ]);

  $trips = json_decode((string) $response->getBody(), true);

  return $this->twig->render("home.html.twig", [
    "api_status" => $response->getStatusCode(),
    "trips" => $trips,
  ]);
});

$app->get("/trips/:id/locations", function ($params) {
  requireLogin($this);

  $response = $this->client->request("GET", "/api/trips/" . $params["id"], [
    'headers' => authHeaders(),
  ]);

  $trip = json_decode((string)$response->getBody(), true);

  return $this->twig->render("trip-locations.html.twig", [
    "trip" => $trip,
  ]);
});

$app->post("/trips/:id/delete", function ($params) {
  requireLogin($this);

  try {
    $this->client->request('DELETE', 'api/trips/' . $params['id']);

    $this->response->mime = 'json';
    $this->stop(['success' => true], 200);
  } catch (\GuzzleHttp\Exception\GuzzleException $e) {
    $this->response->mime = 'json';
    $this->stop([
      'success' => false,
      'error' => $e->getMessage()
    ], 500);
  }
});

$app->on("after", function () {
  switch ($this->response->status) {
    case "404":
    case "500":
      $this->response->body = $this->twig->render(sprintf("%s.html.twig", $this->response->status));
      break;
  }
});

$app->run();
