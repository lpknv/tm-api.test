<?php

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

$app->get("/", function () {
  $response = $this->client->request('GET', 'api/trips');

  $status = $response->getStatusCode();
  $body = (string) $response->getBody();
  $trips = json_decode($body, true);

  return $this->twig->render("home.html.twig", [
    "api_status" => $status,
    "trips" => $trips,
  ]);
});

$app->get("/trips/:id/locations", function ($params) {
  $response = $this->client->request("GET", "api/trips/" . $params["id"]);

  $body = (string)$response->getBody();
  $trip = json_decode($body, true);

  return $this->twig->render("trip-locations.html.twig", [
    "trip" => $trip,
  ]);
});

$app->post("/trips/:id/delete", function ($params) {

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
