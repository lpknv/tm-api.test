<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../database.php';

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
    'route' => 'baseballcamp',
    'data' => [
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
    ],
    'show' => true
  ],
  '/kontakt' => [
    'route' => 'contact',
    'show' => false
  ],
  '/veranstaltungen' => [
    'route' => 'events',
    'show' => false
  ],
  '/predigten' => [
    'route' => 'preaches',
    'show' => false
  ],
  '/baseballcamp' => [
    'route' => 'baseballcamp',
    'show' => false,
    'methods' => [
      'GET' => 'do stuff here',
      'POST' => 'do stuff there',
    ]
  ],
];

if (key_exists($uri, $routes) && $routes[$uri]['show']) {
  echo $twig->render(sprintf("%s.html.twig", $routes[$uri]['route']), $routes[$uri]['data'] ??= []);
} else {
  echo $twig->render('404.html.twig');
}
