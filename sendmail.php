<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond('Method not allowed', 405);
}

$smtp_debug = IS_DEBUG ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

$hp = trim($_POST['hp'] ?? '');
if ($hp !== '') {
  respond('Vielen Dank für deine Anmeldung!', 200);
}

if (count($_POST['kids']) > MAX_KIDS_NUMBER) {
  respond("Oops!", 400);
}

$kids_array = $_POST['kids'];
$how_did_you_find_out_about_us = trim($_POST['how_did_you_find_out_about_us'] ?? '');
$infos = trim($_POST['infos']) ?? '';

$validator = new Validator($_POST, [
  'familienname' => [
    'label' => 'Familienname',
    'rules' => 'required|min:2|max:50',
  ],
  'email' => [
    'label' => 'E-Mail-Adresse',
    'rules' => 'required|email',
  ],
  'telefonnummer' => [
    'label' => 'Telefonnummer',
    'rules' => 'required|min:6|max:50',
  ],
  'strasse_hausnummer' => [
    'label' => 'Straße + Hausnummer',
    'rules' => 'required|min:2|max:100',
  ],
  'plz' => [
    'label' => 'Postleitzahl',
    'rules' => 'required|min:1|max:50',
  ],
  'ort' => [
    'label' => 'Ort',
    'rules' => 'required|min:6|max:50',
  ],
  'datenschutz' => [
    'label' => 'Datenschutz',
    'rules' => 'accepted',
  ],
  'agb' => [
    'label' => 'AGB',
    'rules' => 'accepted',
  ],
  'kids' => [
    'label' => 'Teilnehmer',
    'rules' => [
      '_self' => 'required|min:1|max:' . MAX_KIDS_NUMBER,
      'name' => 'required|min:6|max:50',
      'alter' => 'required|min:1|max:2',
      'tshirt' => 'required',
      'heimweg' => 'required'
    ]
  ],
]);

$validator->validate();
var_dump($validator->errors());
exit;

if (!$validator->validate()) {
  respond([
    'message' => 'Bitte überprüfe deine Eingaben',
    'errors' => $validator->errors(),
  ], 400);
}

$familienname = $validator->data()['familienname'];
$email = $validator->data()['email'];
$telefonnummer = $validator->data()['telefonnummer'];
$strasse_hausnummer = $validator->data()['strasse_hausnummer'];
$plz = $validator->data()['plz'];
$ort = $validator->data()['ort'];
$datenschutz = $validator->data()['datenschutz'];
$agb = $validator->data()['agb'];
$kids = $validator->data()['kids'];

$more_than_one_kid = count($kids_array) > 1;
$total_pricing = FIRST_KID_PRICE;

if ($more_than_one_kid) {
  $total_pricing += NTH_KID_PRICE;
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

foreach ($kids as $key => $kid) {
  if ($kid['name'] === '' || $kid['alter'] === '' || $kid['heimweg'] === '' || $kid['tshirt'] === '' || $kid['heimweg'] === '') {
    respond('Bitte fülle alle Pflichtfelder aus', 400);
  }
  $anmeldedaten .= kid_template($kid, $key + 1, $key > 0 ? NTH_KID_PRICE : FIRST_KID_PRICE);
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

if (IS_DEBUG) {
  respond(['message' => $anmeldedaten . "\r\n\r\n" . $nachrichtAnTeilnehmer]);
} else {
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
    $mail->Body .= "</body></html>";

    $mail->send();

    respond([
      'message' => [
        'title' => 'Vielen Dank für deine Anmeldung!',
        'text' => 'Bitte überprüfe dein E-Mail Postfach und Spam Ordner auf eine Bestätigungsemail.'
      ],
    ]);
  } catch (Exception $e) {
    respond(
      "Oops! Etwas ist schief gelaufen. {$mail->ErrorInfo}",
      400
    );
  }
}
