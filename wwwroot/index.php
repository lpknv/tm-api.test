<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../helper.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
  '/' => 'home',
  '/kontakt' => 'contact',
  '/baseballcamp' => 'baseballcamp',
];

if (key_exists($uri, $routes)) {
  include __DIR__ . "/../views/$routes[$uri].view.php";
}
