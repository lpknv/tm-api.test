<?php

declare(strict_types=1);

setlocale(LC_ALL, 'de_DE');
date_default_timezone_set('Europe/Berlin');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';

$envFile = file_exists(__DIR__ . '/.env.dev')
  ? '.env.dev'
  : '.env';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, $envFile);
$dotenv->load();

define('BASE_PATH', dirname($_SERVER['DOCUMENT_ROOT']));
define('MAX_KIDS_NUMBER', 5);
define('FIRST_KID_PRICE', 80);
define('NTH_KID_PRICE', 70);
define('FIRST_KID_PRICE_FORMATTED', format_currency(FIRST_KID_PRICE));
define('NTH_KID_PRICE_FORMATTED', format_currency(NTH_KID_PRICE));
define('CURRENT_YEAR', date('Y'));

define('APP_ENV', $_ENV['APP_ENV']);
define(
  'IS_DEBUG',
  filter_var($_ENV['IS_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)
);
define('IS_DEV', APP_ENV === 'dev');

ini_set('display_errors', IS_DEV ? '1' : '0');
error_reporting(IS_DEV ? E_ALL : 0);
