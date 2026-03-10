<?php

ob_start();
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

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  ob_end_clean();
  respond('Method not allowed', 405);
}

// $cacheAdapter = new DesarrollaCacheAdapter((new DesarrollaCacheFactory())->make());
// $settings = new ElasticWindowSettings(3, 600);
// $ratelimiter = new RateLimiter(new ThrottlerFactory($cacheAdapter), new HydratorFactory(), $settings);

// $loginThrottler = $ratelimiter->get('/login');

$smtp_debug = IS_DEBUG ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

$hp = trim(e($_POST['hp']) ?? '');
if ($hp !== '') {
  ob_end_clean();
  respond('Vielen Dank für deine Anmeldung!', 200);
}

if (count($_POST['kids']) > MAX_KIDS_NUMBER) {
  ob_end_clean();
  respond("Oops!", 400);
}

$kids_array = $_POST['kids'];

$validator = new Validator;

$validator->setMessages([
  'required' => 'Bitte :attribute angeben',
  'email' => 'Üngultige E-Mail Adresse :email',
  'min' => ':attribute ist zu kurz',
  'max' => ':attribute ist zu lang',
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
  'kids.*.alter'   => 'required',
  'kids.*.tshirt'   => 'required',
  'kids.*.heimweg'   => 'required',
]);

$aliases = [];

foreach ($kids_array as $i => $kid) {
  $nr = $i + 1;

  $aliases["kids.$i.name"] = "Name von Kind $nr";
  $aliases["kids.$i.alter"] = "Alter von Kind $nr";
  $aliases["kids.$i.tshirt"] = "T-Shirt Größe von Kind $nr";
  $aliases["kids.$i.heimweg"] = "Nach dem Baseballcamp selbständig den Heimweg antreten? von Kind $nr";
  $aliases["kids.$i.height"] = "Körpergröße von Kind $nr";
}

$validation->setAliases(array_merge($aliases, $form_label_aliases));

$validation->validate();

if ($validation->fails()) {
  ob_end_clean();
  $errors = $validation->errors();
  respond(['errors' => $errors->all()], 400);
}

$validData = $validation->getValidData();

$familienname = $validData['familienname'];
$email = $validData['email'];
$telefonnummer = $validData['telefonnummer'];
$strasse_hausnummer = $validData['strasse_hausnummer'];
$plz = $validData['plz'];
$ort = $validData['ort'];
$datenschutz = $validData['datenschutz'];
$agb = $validData['agb'];
$kids = $validData['kids'];
$how_did_you_find_out_about_us = trim($_POST['how_did_you_find_out_about_us'] ?? '- keine Angabe -');
$infos = trim($_POST['infos']) ?? '- keine Angabe -';

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

$gesamtpreis = "<p><strong>Zu zahlender Gesamtbetrag: $total_pricing,- Euro</strong></p>";

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
  $mail->isSMTP();
  $mail->SMTPDebug = $smtp_debug;
  $mail->CharSet = 'UTF-8';
  $mail->Host = $_ENV['SMTP_HOST'];
  $mail->SMTPAuth = true;
  $mail->Port = $_ENV['SMTP_PORT'];
  $mail->Username = $_ENV['SMTP_USER'];
  $mail->Password = $_ENV['SMTP_PASSWORD'];

  $mail->setFrom($_ENV['MAIL']);
  $mail->isHTML(true);

  $mail->addAddress($email);
  $mail->Subject = "Bestätigung Ihrer Anmeldung zum Baseballcamp " . CURRENT_YEAR;
  $mail->Body = $nachrichtAnTeilnehmer;
  $mail->Body .= "<h3 style='text-decoration:underline'>Ihre Anmeldedaten im Überblick:</h3>";
  $mail->Body .= $anmeldedaten;
  $mail->send();

  $mail->clearAddresses();
  $mail->clearCCs();
  $mail->clearBCCs();
  $mail->clearReplyTos();
  $mail->clearAttachments();

  $mail->addAddress($_ENV['MAIL']);
  $mail->Subject = sprintf("Neue Baseballcamp %s Anmeldung", CURRENT_YEAR);
  $mail->Body = "<h3 style='text-decoration:underline'>Neue Anmeldung</h3>";
  $mail->Body .= "
    <html>
      <head>
        <title>Neue Baseballcamp Anmeldung " . CURRENT_YEAR . "</title>
        <style>
          html, body {
            font-family: Inter, sans-serif;
          }
        </style>
      </head>            
    <body>";

  $mail->Body .= $anmeldedaten;
  $mail->Body .= $gesamtpreis;
  $mail->Body .= "</body></html>";

  $mail->send();

  ob_end_clean();
  respond([
    'message' => [
      'title' => 'Vielen Dank für deine Anmeldung!',
      'text' => 'Bitte überprüfe dein E-Mail Postfach und Spam Ordner auf eine Bestätigungsemail.'
    ],
  ]);
} catch (Exception $e) {
  ob_end_clean();
  respond(
    "Oops! Etwas ist schief gelaufen. {$mail->ErrorInfo}",
    400
  );
}
