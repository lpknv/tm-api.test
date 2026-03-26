<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../helper.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Berlin');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$upcoming_events = [
  [
    'name' => 'Baseballcamp 2026',
    'text' => 'Jeden Sonntag um 10 Uhr kommen wir zusammen...',
    'date_start' => '2026-06-20',
    'date_end' => '2026-06-25',
    'location' => 'Hückelhoven – Am Schacht 3<br/>(Glück-auf-Stadion)',
  ],
];

$routes = [
  '/' => [
    'route' => 'home',
    'data' => [
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
    ],
  ],
  '/kontakt' => [
    'route' => 'contact',
  ],
  '/predigten' => [
    'route' => 'preaches',
  ],
  '/baseballcamp' => [
    'route' => 'baseballcamp',
  ],
];

if (key_exists($uri, $routes)) {
  echo $twig->render(sprintf("%s.html.twig", $routes[$uri]['route']), $routes[$uri]['data'] ??= []);
}
