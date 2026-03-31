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

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . "/../src/Lime/App.php";

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Berlin');

$upcoming_events = [
  [
    'name' => 'Baseballcamp 2026',
    'text' => 'Jeden Sonntag um 10 Uhr kommen wir zusammen...',
    'date_start' => '2026-06-20',
    'date_end' => '2026-06-25',
    'location' => 'Hückelhoven - Am Schacht 3<br/>(Glück-auf-Stadion)',
  ],
];

$home_data = [
  'ages' => $ages,
  'tshirt_sizes' => $tshirt_sizes,
  'marketing' => $marketing,
  'max_kids_number' => MAX_KIDS_NUMBER,
  'first_kid_price' => FIRST_KID_PRICE,
  'nth_kid_price' => NTH_KID_PRICE,
  'current_year' => CURRENT_YEAR,
  'upcoming_events' => prepare_upcoming_events($upcoming_events),
  'weekly_events' => [
    [
      'name' => 'Gottesdienst, Sonntag 10:00 Uhr',
      'text' => 'Jeden Sonntag um 10 Uhr kommen wir zusammen, um Gott zu feiern und zu erleben.',
      'img' => 'godi'
    ],
    [
      'name' => 'Kinderstunde',
      'text' => 'Während des Gottesdienstes gibt es eine Kinderstunde für Kinder bis einschließlich der 4. Grundschulklasse.',
      'img' => 'kinderstunde'
    ],
    [
      'name' => 'Veranstaltungen',
      'text' => 'Unser Veranstaltungsangebot ist vielseitig und abwechslungsreich – sowohl von uns als auch in Zusammenarbeit mit anderen. Ob online oder vor Ort, für Kinder oder Erwachsene, es ist für jeden etwas dabei',
      'img' => 'events'
    ],
  ]
];

$confirmation_data = [
  'familienname' => 'asdasd',
  'gesamtpreis' => format_currency(150),
];

$app = new App\Lime\App();

$app->get("/test", function () use ($twig, $confirmation_data) {
  echo $twig->render("email/baseballcamp/confirmation.html.twig", $confirmation_data);
});

$app->get("/", function () use ($twig, $home_data) {
  echo $twig->render("home.html.twig", $home_data);
});

$app->get("/baseballcamp", function () use ($twig, $marketing, $ages, $tshirt_sizes) {
  echo $twig->render("baseballcamp.html.twig", [
    'max_kids_number' => MAX_KIDS_NUMBER,
    'first_kid_price' => FIRST_KID_PRICE,
    'nth_kid_price' => NTH_KID_PRICE,
    'marketing' => $marketing,
    'ages' => $ages,
    'tshirt_sizes' => $tshirt_sizes,
  ]);
});

$app->post("/ajax/baseballcamp", function () use ($marketing, $ages, $tshirt_sizes) {
  session_start();

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond('Method not allowed', 405);
  }

  $smtp_debug = IS_DEBUG ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

  $hp = trim(e($_POST['hp']) ?? '');
  if ($hp !== '') {
    respond('Vielen Dank für deine Anmeldung!', 200);
  }

  if (count($_POST['kids']) > MAX_KIDS_NUMBER || count($_POST['kids']) < 1) {
    respond("Oops!", 400);
  }

  $kids_array = $_POST['kids'];

  $validator = new Validator;

  $validator->setMessages([
    'required' => 'Bitte :attribute angeben',
    'email' => 'Üngultige E-Mail Adresse :email',
    'min' => ':attribute ist zu kurz',
    'max' => ':attribute ist zu lang',
    'in' => ':attribute erlaubt nur :allowed_values',
  ]);

  $validator->setTranslations([
    'and' => 'und',
    'or' => 'oder',
  ]);

  $validation = $validator->make($_POST, [
    'familienname' => 'required|min:2|max:50',
    'email'                 => 'required|email',
    'telefonnummer' => 'required|min:2|max:50',
    'strasse_hausnummer' => 'required|min:2|max:50',
    'plz' => 'required|min:2|max:50',
    'ort' => 'required|min:2|max:50',
    'datenschutz' => 'required',
    'agb' => 'required',
    'kids'                => 'array',
    'kids.*.name'           => 'required|min:2|max:50',
    'kids.*.alter'   => 'required|in:' . implode(',', $ages),
    'kids.*.tshirt'   => 'required|in:' . implode(',', $tshirt_sizes),
    'kids.*.heimweg'   => 'required|in:Ja,Nein',
    'kids.*.height'   => 'nullable|min:2|max:5',
    'how_did_you_find_out_about_us' => 'nullable|in:' . implode(',', $marketing),
    'infos' => 'nullable|max:500'
  ]);

  $aliases = [];

  foreach ($kids_array as $i => $kid) {
    $nr = $i + 1;

    $aliases["kids.$i.name"] = "\"Name\" von Kind $nr";
    $aliases["kids.$i.alter"] = "\"Alter\" von Kind $nr";
    $aliases["kids.$i.tshirt"] = "\"T-Shirt Größe\" von Kind $nr";
    $aliases["kids.$i.heimweg"] = "\"Nach dem Baseballcamp selbständig den Heimweg antreten?\" von Kind $nr";
    $aliases["kids.$i.height"] = "\"Körpergröße\" von Kind $nr";
  }

  $validation->setAliases(array_merge(
    [
      'familienname'                  => 'Familienname',
      'email'                         => 'E-Mail Adresse',
      'telefonnummer'                 => 'Telefonnummer',
      'strasse_hausnummer'            => 'Straße + Hausnummer',
      'plz'                           => 'Postleitzahl',
      'ort'                           => 'Ort',
      'datenschutz'                   => 'Datenschutzerklärung',
      'agb'                           => 'Allgemeine Geschäftsbedingungen',
      'kids'                          => 'Teilnehmer',
      'how_did_you_find_out_about_us' => '\"Wie bist du auf unser Baseballcamp aufmerksam geworden?\"',
      'infos'                         => '\"Weitere Informationen (Krankheiten, Allergien usw.)\"',
    ],
    $aliases
  ));

  $validation->validate();

  if ($validation->fails()) {
    $errors = $validation->errors();
    respond(['errors' => $errors->all()], 400);
  }

  $validData = $validation->getValidData();

  $familienname = trim(e($validData['familienname']));
  $email = trim(e($validData['email']));
  $telefonnummer = trim(e($validData['telefonnummer']));
  $strasse_hausnummer = trim(e($validData['strasse_hausnummer']));
  $plz = trim(e($validData['plz']));
  $ort = trim(e($validData['ort']));
  $datenschutz = $validData['datenschutz'];
  $agb = $validData['agb'];
  $kids = $validData['kids'];
  $how_did_you_find_out_about_us = trim(e($validData['how_did_you_find_out_about_us']) ?? '- keine Angabe -');
  $infos = trim(e($validData['infos'])) ?? '- keine Angabe -';

  $total_pricing = FIRST_KID_PRICE;

  $anmeldedaten = "
<p>
  <strong>Familienname:</strong> $familienname<br/>
  <strong>E-Mail-Adresse:</strong> $email<br/>
  <strong>Telefonnummer / Handynummer der Eltern:</strong> $telefonnummer<br/>
  <strong>Straße + Hausnummer:</strong> $strasse_hausnummer<br/>
  <strong>Postleitzahl:</strong> $plz<br/>
  <strong>Ort:</strong> $ort<br/>
</p>
";

  foreach ($kids as $key => $kid) {
    $nth_child = $key > 0;
    if ($nth_child) $total_pricing += NTH_KID_PRICE;
    $anmeldedaten .= kid_template($kid, $key + 1, $nth_child ? NTH_KID_PRICE : FIRST_KID_PRICE);
  }

  $datenschutzAkzeptiert = isset($datenschutz) ? "✅" : "❌";
  $agbAkzeptiert = isset($agb) ? "✅" : "❌";

  $anmeldedaten .= "<h4>Allgemeine Informationen</h4>
<p>
<strong>Wie bist du auf unser Baseballcamp aufmerksam geworden: </strong> $how_did_you_find_out_about_us<br/>
<strong>Weitere Informationen (Krankheiten, Allergien usw.): </strong> <br/>
$infos
<br/>
<strong>Zustimmung Datenschutz:</strong> $datenschutzAkzeptiert<br/>
<strong>Zustimmung AGB:</strong> $agbAkzeptiert<br/>
</p>
";

  $gesamtpreis = sprintf("<p><strong style=\"border-bottom: 2px solid black;\">Zu zahlender Gesamtbetrag: %s</strong></p>", format_currency($total_pricing));

  $nachrichtAnTeilnehmer = "<html>
<head>
  <title>Baseballcamp " . CURRENT_YEAR . "</title>
  <style>
    html, body {
      font-family: Inter, sans-serif;
    }
  </style>
</head>            
<body>
  <p>
    Hallo Familie $familienname,
  </p>
  <p>
    herzlich Willkommen zum Baseballcamp " . CURRENT_YEAR . "!
    <br/>
    <br/>
    Hiermit bestätigen wir die Anmeldung und freuen uns schon auf das Event.
    <br/>
    <br/>
    Bitte überweißt den unten genannten Betrag, damit die Anmeldung abgeschlossen werden kann.
    <br/>
    Solltet ihr noch Fragen oder Korrektur zu Anmeldedaten haben, dann schreibt uns gerne per E-mail an.
    <br/>
    <br/>
    Nach Zahlungseingang erhaltet ihr dann eine Zahlungsbestätigung von uns.
    <br/>
    <br/>
    Das BBC-Team freut sich auf eine tolle und spannende Zeit 🙂
    <br/>
    <br/>
  </p>

  <h3>Ihre Anmeldung im Überblick:</h3>
  $gesamtpreis
  <p>
    <strong>Unsere Bankverbindung:</strong>
    <br/>
    Evangelisch-Freikirchliche Gemeinde Hückelhoven-Baal
    <br/>
    IBAN: DE88 3125 1220 0002 8023 87
    <br/>
    BIC: WELADED1ERK
    <br/>
    Kreditinstitut: Kreisparkasse Heinsberg
    <br/>
    Verwendung: Baseball Camp " . CURRENT_YEAR . " und $familienname
    <br/>
    Berechnung des Betrags: 1. Kind EUR " . FIRST_KID_PRICE . ",- (Geschwisterkinder: EUR " . NTH_KID_PRICE . ",-)
  </p>
</body>
</html>
";

  $mail = new PHPMailer(true);

  try {
    send_email($mail, [
      'smtp_debug' => $smtp_debug,
      'email' => $_ENV['MAIL'],
      'subject' => "Neue Baseballcamp Anmeldung " . CURRENT_YEAR,
      'body' => "
      <html>
      <head>
        <title>Baseballcamp " . CURRENT_YEAR . "</title>
        <style>
          html, body {
            font-family: Inter, sans-serif;
          }
        </style>
      </head>
      <body>
      <h3>Neue Anmeldung</h3>" . $anmeldedaten . $gesamtpreis . "</body></html>",
    ]);
  } catch (Exception $e) {
    error_log('Admin-Mail fehlgeschlagen: ' . $mail->ErrorInfo);
  }

  if (IS_DEV) sleep(10);

  try {
    send_email($mail, [
      'smtp_debug' => $smtp_debug,
      'email' => $email,
      'subject' => "Bestätigung Ihrer Anmeldung zum Baseballcamp " . CURRENT_YEAR,
      'body' => $nachrichtAnTeilnehmer . "<h3>Ihre Anmeldedaten:</h3>" . $anmeldedaten,
    ]);

    respond([
      'message' => [
        'title' => 'Vielen Dank.',
        'text' => 'Ihre Anmeldung wurde erfolgreich übermittelt.',
      ]
    ]);
  } catch (Exception $e) {
    error_log('Kundenmail fehlgeschlagen: ' . $mail->ErrorInfo);

    respond([
      'success' => false,
      'message' => 'Die Bestätigungsmail konnte nicht gesendet werden.' . $mail->ErrorInfo
    ], 500);
  }
});

$app->on("after", function () use ($twig) {
  switch ($this->response->status) {
    case "404":
    case "500":
      $this->response->body = $twig->render(sprintf("%s.html.twig", $this->response->status));
      break;
  }
});

$app->run();
