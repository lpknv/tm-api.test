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
error_reporting(E_ALL);

$smtp_debug = IS_DEV ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

if (IS_DEV) {
  ini_set('display_errors', '1');
} else {
  ini_set('display_errors', '0');
  error_reporting(E_ALL);
}

$hp = trim($_POST['hp'] ?? '');
if ($hp !== '') {
  respond('Vielen Dank für deine Anmeldung!', 200, true);
}


$familienname       = trim($_POST['familienname']);
$email              = trim($_POST['email']);
$telefonnummer       = trim($_POST['telefonnummer']);
$strasse_hausnummer  = trim($_POST['strasse_hausnummer']);
$plz                = trim($_POST['plz']);
$ort                = trim($_POST['ort']);

$name1              = trim($_POST['name1']);
$alter1             = trim($_POST['alter1']);
$heimweg1           = trim($_POST['heimweg1']);
$tshirt1            = trim($_POST['tshirt1']);

$name2              = trim($_POST['name2']);
$alter2             = trim($_POST['alter2']);
$heimweg2           = trim($_POST['heimweg2']);
$tshirt2            = trim($_POST['tshirt2']);

$name3              = trim($_POST['name3']);
$alter3             = trim($_POST['alter3']);
$heimweg3           = trim($_POST['heimweg3']);
$tshirt3            = trim($_POST['tshirt3']);

$name4              = trim($_POST['name4']);
$alter4             = trim($_POST['alter4']);
$heimweg4           = trim($_POST['heimweg4']);
$tshirt4            = trim($_POST['tshirt4']);

$name5              = trim($_POST['name5']);
$alter5             = trim($_POST['alter5']);
$heimweg5           = trim($_POST['heimweg5']);
$tshirt5            = trim($_POST['tshirt5']);

$marketing = $_POST['marketing'];
$infos = $_POST['infos'];

$datenschutz = $_POST['datenschutz'];
$agb = $_POST['agb'];

$response = [
  'message' => '',
  'success' => false,
];

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

if (
  $familienname === '' || $email === '' || $telefonnummer === '' || $strasse_hausnummer === '' ||
  $plz === '' || $ort === '' || $name1 === '' || $alter1 === '' || $heimweg1 === '' || $tshirt1 === ''
) {
  respond('Bitte fülle alle Pflichtfelder aus', 400, false);
}

$preis = 70;

$year = CURRENT_YEAR;

$anmeldedaten = "
<p>
  <strong>Familienname:</strong> $familienname<br/>
  <strong>E-Mail-Adresse:</strong> $email<br/>
  <strong>Telefonnummer / Handynummer der Eltern:</strong> $telefonnummer<br/>
  <strong>Straße + Hausnummer:</strong> $strasse_hausnummer<br/>
  <strong>Postleitzahl:</strong> $plz<br/>
  <strong>Ort:</strong> $ort
</p>
 
<p>
  <strong>Kind 1</strong><br/>
  <strong>Name (Kosten 70,- Euro):</strong> $name1<br/>
  <strong>Alter:</strong> $alter1<br/>
  <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> $heimweg1<br/>
  <strong>T-Shirt-Größe:</strong> $tshirt1<br/>
</p>
";

if (!empty($name2) && isset($name2)) {
  if (
    !isset($alter2) || !isset($heimweg2) || !isset($tshirt2)
  ) {
    respond('Bitte fülle alle Pflichtfelder aus', 400);
  }

  $preis += 60;

  $anmeldedaten .= "
  <p>
    <strong>Kind 2</strong><br/>
    <strong>Name (Kosten 60,- Euro):</strong> $name2<br/>
    <strong>Alter:</strong> $alter2<br/>
    <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> $heimweg2<br/>
    <strong>T-Shirt-Größe:</strong> $tshirt2<br/>
  </p>
  ";
}

if (!empty($name3) && isset($name3)) {
  if (
    !isset($alter3) || !isset($heimweg3) || !isset($tshirt3)
  ) {
    respond('Bitte fülle alle Pflichtfelder aus', 400);
  }

  $preis += 60;

  $anmeldedaten .= "
  <p>
    <strong>Kind 3</strong><br/>
    <strong>Name (Kosten 60,- Euro):</strong> $name3<br/>
    <strong>Alter:</strong> $alter3<br/>
    <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> $heimweg3<br/>
    <strong>T-Shirt-Größe:</strong> $tshirt3<br/>
  </p>
  ";
}

if (!empty($name4) && isset($name4)) {
  if (
    !isset($alter4) || !isset($heimweg4) || !isset($tshirt4)
  ) {
    respond('Bitte fülle alle Pflichtfelder aus', 400);
  }

  $preis += 60;

  $anmeldedaten .= "
  <p>
    <strong>Kind 4</strong><br/>
    <strong>Name (Kosten 60,- Euro):</strong> $name4<br/>
    <strong>Alter:</strong> $alter4<br/>
    <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> $heimweg4<br/>
    <strong>T-Shirt-Größe:</strong> $tshirt4<br/>
  </p>
  ";
}

if (!empty($name5) && isset($name5)) {
  if (
    !isset($alter5) || !isset($heimweg5) || !isset($tshirt5)
  ) {
    respond('Bitte fülle alle Pflichtfelder aus', 400);
  }

  $preis += 60;

  $anmeldedaten .= "
  <p>
    <strong>Kind 5</strong><br/>
    <strong>Name (Kosten 60,- Euro):</strong> $name5<br/>
    <strong>Alter:</strong> $alter5<br/>
    <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> $heimweg5<br/>
    <strong>T-Shirt-Größe:</strong> $tshirt5<br/>
  </p>
  ";
}

$datenschutzAkzeptiert = isset($datenschutz) ? "✅" : "❌";
$agbAkzeptiert = isset($agb) ? "✅" : "❌";

$gesamtbetrag = "<p><strong>Zu zahlender Gesamtbetrag: $preis,- Euro</strong></p>";

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
  <title>Baseballcamp $year</title>
</head>            
<body>
  <p>
    Hallo Familie $familienname,
  </p>
  <p>
    Herzlich Willkommen zum Baseballcamp $year!
    <br/>
    <br/>
    Hiermit bestätigen wir die Anmeldung und freuen uns schon auf das Event.
    <br/>
    Bitte überweißt den unten genannten Betrag, damit die Anmeldung abgeschlossen werden kann.
    <br/>
    Solltet ihr noch Fragen oder Korrektur zu Anmeldedaten haben, dann schreibt uns gerne per E-mail an.
    <br/>
    Nach Zahlungseingang erhaltet ihr dann eine Zahlungsbestätigung von uns.
    <br/>
    Das BBC-Team freut sich auf eine tolle und spannende Zeit 🙂
  </p>

  <h3>Ihre Anmeldung im Überblick:</h3>
  $gesamtbetrag
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
    Verwendung: Baseball Camp $year und $familienname
    <br/>
    Berechnung des Betrags: 1. Kind EUR 70,- (Geschwisterkinder: EUR 60,-)
  </p>
</body>
</html>
";

$mail = new PHPMailer();

try {
  $mail->SMTPDebug = $smtp_debug;
  $mail->CharSet = getenv('SMTP_CHARSET');
  $mail->Host = getenv('SMTP_HOST');
  $mail->SMTPAuth = true;
  $mail->Port = getenv('SMTP_PORT');
  $mail->Username = getenv('SMTP_USER');
  $mail->Password = getenv('SMTP_PASSWORD');
  if(!IS_DEV) {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  }

  $mail->setFrom('baseballcamp@efg-hueckelhoven.de');
  $mail->addAddress($email);

  $mail->isHTML(true);
  $mail->Subject = "Bestätigung Ihrer Anmeldung zum Baseballcamp $year";
  $mail->Body = $nachrichtAnTeilnehmer;
  $mail->Body .= "<h3 style='text-decoration:underline'>Ihre Anmeldedaten im Überblick:</h3>";
  $mail->Body .= $anmeldedaten;

  $text = $nachrichtAnTeilnehmer;
  $text .= "<h3 style='text-decoration:underline'>Ihre Anmeldedaten im Überblick:</h3>";
  $text .= $anmeldedaten;

  respond("Vielen Dank für deine Anmeldung! Wir melden uns schnellstmöglich bei dir.", 200, true);
} catch (Exception $e) {
  respond("Oops! Etwas ist schief gelaufen. Versuche es später erneut. {$mail->ErrorInfo}", 400);
}

$mail1 = new PHPMailer();

try {
  $mail1->SMTPDebug = $smtp_debug;
  $mail1->CharSet = getenv('SMTP_CHARSET');
  $mail1->Host = getenv('SMTP_HOST');
  $mail1->SMTPAuth = true;
  $mail1->Port = getenv('SMTP_PORT');
  $mail1->Username = getenv('SMTP_USER');
  $mail1->Password = getenv('SMTP_PASSWORD');
  if(!IS_DEV) {
    $mail1->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  }

  $mail1->setFrom('baseballcamp@efg-hueckelhoven.de');
  $mail1->addAddress('baseballcamp@efg-hueckelhoven.de');

  $mail1->isHTML(true);
  $mail1->Subject = "Neue Baseballcamp Anmeldung $year";
  $mail1->Body    = "<h3 style='text-decoration:underline'>Neue Baseballcamp Anmeldung $year</h3>";;
  $mail1->Body    .= $anmeldedaten;

  respond("Vielen Dank für deine Anmeldung! Wir melden uns schnellstmöglich bei dir.", 200, true);
} catch (Exception $e) {
  respond("Oops! Etwas ist schief gelaufen. Versuche es später erneut. {$mail->ErrorInfo}", 400);
}
