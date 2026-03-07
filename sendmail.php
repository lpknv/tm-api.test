<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond('Method not allowed', 405, false);
}

ini_set('display_errors', IS_DEV ? '1' : '0');
error_reporting(IS_DEV ? E_ALL : 0);

$smtp_debug = IS_DEBUG ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

$hp = trim($_POST['hp'] ?? '');
if ($hp !== '') {
  respond('Vielen Dank für deine Anmeldung!', 200, true);
}

if (count($_POST['kids']) > MAX_KIDS_NUMBER) {
  respond("Error!", 400);
}

$familienname       = trim($_POST['familienname']);
$email              = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
$telefonnummer       = trim($_POST['telefonnummer']);
$strasse_hausnummer  = trim($_POST['strasse_hausnummer']);
$plz                = trim($_POST['plz']);
$ort                = trim($_POST['ort']);
$kids_array = $_POST['kids'];

$marketing = $_POST['marketing'];
$infos = $_POST['infos'];

$datenschutz = $_POST['datenschutz'];
$agb = $_POST['agb'];

if (
  $familienname === '' || $email === '' || $telefonnummer === '' || $strasse_hausnummer === '' ||
  $plz === '' || $ort === '' || empty($kids_array)
) {
  respond('Bitte fülle alle Pflichtfelder aus', 400, false);
}

$response = [
  'message' => '',
  'success' => false,
];

$anmeldedaten = "";

function respond($message, $statusCode = 200, $success = false)
{
  http_response_code($statusCode);
  header('Content-Type: application/json; charset=utf-8');

  echo json_encode([
    'success' => (bool)$success,
    'message' => $message
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$price_per_kid = FIRST_KID_PRICE;

$more_than_one_kid = count($kids_array) > 1;

$total_pricing = FIRST_KID_PRICE;

if ($more_than_one_kid) {
  $total_pricing += NTH_KID_PRICE;
}

function kid_template($kid, $index, $price)
{
  return sprintf(
    "\r<p>
      <strong>Kind %d</strong><br/>
      <strong>Name (Kosten %d,- Euro):</strong> %s<br/>
      <strong>Alter:</strong> %s<br/>
      <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> %s<br/>
      <strong>T-Shirt-Größe:</strong> %s
    </p>\r\n",
    $index,
    $price,
    $kid['name'],
    $kid['alter'],
    $kid['heimweg'],
    $kid['tshirt']
  );
}

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

foreach ($kids_array as $key => $kid) {
  if ($kid['name'] === '' || $kid['alter'] === '' || $kid['heimweg'] === '' || $kid['tshirt'] === '' || $kid['heimweg'] === '') {
    respond('Bitte fülle alle Pflichtfelder aus', 400, false);
  }
  $anmeldedaten .= kid_template($kid, $key + 1, $key > 0 ? NTH_KID_PRICE : FIRST_KID_PRICE);
}

$datenschutzAkzeptiert = isset($datenschutz) ? "✅" : "❌";
$agbAkzeptiert = isset($agb) ? "✅" : "❌";

$anmeldedaten .= "<h4>Allgemeine Informationen</h4>
<p>
<strong>Wie bist du auf unser Baseballcamp aufmerksam geworden: </strong> $marketing<br/>
<strong>Weitere Informationen (Krankheiten, Allergien usw.): </strong> <br/>
$infos
<br/>
<strong>Zustimmung Datenschutz:</strong> $datenschutzAkzeptiert<br/>
<strong>Zustimmung AGB:</strong> $agbAkzeptiert<br/>
</p>
";

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
  <p><strong>Zu zahlender Gesamtbetrag: $total_pricing,- Euro</strong></p>
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

  $mail->setFrom('baseballcamp@efg-hueckelhoven.de');
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

  $mail->addAddress('baseballcamp@efg-hueckelhoven.de');
  $mail->Subject = "Neue Baseballcamp Anmeldung " . CURRENT_YEAR;
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
  $mail->Body .= "</body></html>";
  $mail->send();

  respond(
    "Vielen Dank für deine Anmeldung! Wir melden uns schnellstmöglich bei dir.",
    200,
    true
  );
} catch (Exception $e) {
  respond(
    "Oops! Etwas ist schief gelaufen. {$mail->ErrorInfo}",
    400
  );
}
