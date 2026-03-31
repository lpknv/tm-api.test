<?php

function respond($message = null, int $statusCode = 200, $exit = true)
{
  http_response_code($statusCode);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($message, JSON_UNESCAPED_UNICODE);

  if ($exit) exit;
}

function kid_template($kid, $index, $price)
{
  $heightLine = '';

  if (!empty($kid['height'])) {
    $heightLine = sprintf(
      "<strong>Körpergröße:</strong> %s<br/>",
      $kid['height']
    );
  }

  return sprintf(
    "\r<p>
      <strong style=\"border-bottom: 1px solid #444;\">Kind %d</strong><br/>
      <strong>Name (Kosten %s):</strong> %s<br/>
      <strong>Alter:</strong> %s<br/>
      <strong>T-Shirt-Größe:</strong> %s<br/>
      %s
      <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> %s
    </p>\r\n",
    $index,
    format_currency($price),
    $kid['name'],
    $kid['alter'],
    $kid['tshirt'],
    $heightLine,
    $kid['heimweg'],
  );
}

function e(?string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_currency($value)
{
  $a = new \NumberFormatter("de-DE", \NumberFormatter::CURRENCY);
  $a->setPattern('EUR #,##0.00');
  return $a->format($value);
}

function intl_date(string $value, string $pattern = 'dd. MMMM yyyy', string $locale = 'de_DE'): string
{
  $date = new \DateTimeImmutable($value);

  $formatter = new \IntlDateFormatter(
    $locale,
    \IntlDateFormatter::NONE,
    \IntlDateFormatter::NONE,
    $date->getTimezone(),
    null,
    $pattern
  );

  return $formatter->format($date);
}

function prepare_upcoming_events(array $events): array
{
  $visibleEvents = [];

  foreach ($events as $event) {
    if (!event_is_visible($event['date_end'])) {
      continue;
    }

    $event['date_start'] = format_datetime($event['date_start']);
    $event['date_end'] = format_datetime($event['date_end']);

    $visibleEvents[] = $event;
  }

  return $visibleEvents;
}

function format_datetime(string $value): array
{
  return [
    'day' => intl_date($value, 'dd'),
    'month' => intl_date($value, 'MMMM'),
    'year' => intl_date($value, 'yyyy'),
    'full' => intl_date($value, 'dd. MMMM yyyy'),
  ];
}

function event_is_visible($end)
{
  $now = new \DateTimeImmutable('today');
  $endDate = new \DateTimeImmutable($end);

  return $now <= $endDate;
}

function format_datetime_parts(string $value, string $locale = 'de_DE'): array
{
  $date = new \DateTimeImmutable($value);

  $format = function (string $pattern) use ($date, $locale) {
    $formatter = new \IntlDateFormatter(
      $locale,
      \IntlDateFormatter::NONE,
      \IntlDateFormatter::NONE,
      $date->getTimezone(),
      null,
      $pattern
    );

    return $formatter->format($date);
  };

  return [
    'day' => $format('dd'),
    'month' => $format('MMMM'),
    'year' => $format('yyyy'),
    'full' => $format('dd. MMMM yyyy'),
  ];
}


function send_email($mail, $data)
{
  $mail->clearAddresses();
  $mail->clearCCs();
  $mail->clearBCCs();
  $mail->clearReplyTos();
  $mail->clearAttachments();

  $mail->isSMTP();
  $mail->SMTPDebug = $data['smtp_debug'] ?? 0;
  $mail->CharSet = 'UTF-8';
  $mail->Host = $_ENV['SMTP_HOST'];
  $mail->SMTPAuth = true;
  $mail->Port = $_ENV['SMTP_PORT'];
  $mail->Username = $_ENV['SMTP_USER'];
  $mail->Password = $_ENV['SMTP_PASSWORD'];

  $mail->setFrom($_ENV['MAIL']);
  $mail->isHTML(true);

  $mail->addAddress($data['email']);
  $mail->Subject = $data['subject'];
  $mail->Body = $data['body'];

  $mail->send();
}
