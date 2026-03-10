<?php

require __DIR__ . '/vendor/autoload.php';

use MatthiasMullie\Minify;

$minifier = new Minify\CSS();

$minifier->add(__DIR__ . '/wwwroot/assets/main.css');
$minifier->add(__DIR__ . '/wwwroot/assets/theme.css');

$minifiedPath = __DIR__ . '/wwwroot/assets/minified/main.min.css';

$dir = dirname($minifiedPath);

if (!is_dir($dir)) {
  mkdir($dir, 0777, true);
}

$minifier->minify($minifiedPath);

echo "minified ok\n";
