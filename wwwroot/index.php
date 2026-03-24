<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../helper.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, ['debug' => true]);

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
  '/' => 'home',
  '/kontakt' => 'contact',
  '/baseballcamp' => 'baseballcamp',
];

if (key_exists($uri, $routes)) {
  echo $twig->render("$routes[$uri].html.twig");
}
