<?php

function respond($message = null, $statusCode = 200)
{
  http_response_code($statusCode);
  header('Content-Type: application/json; charset=utf-8');

  echo json_encode($message, JSON_UNESCAPED_UNICODE);

  exit;
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
      <strong>Kind %d</strong><br/>
      <strong>Name (Kosten %d,- Euro):</strong> %s<br/>
      <strong>Alter:</strong> %s<br/>
      <strong>T-Shirt-Größe:</strong> %s<br/>
      %s
      <strong>Nach dem Baseballcamp selbständig den Heimweg antreten?:</strong> %s
    </p>\r\n",
    $index,
    $price,
    $kid['name'],
    $kid['alter'],
    $kid['tshirt'],
    $heightLine,
    $kid['heimweg'],
  );
}
