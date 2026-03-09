<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('MAX_KIDS_NUMBER', 5);
define('FIRST_KID_PRICE', 80);
define('NTH_KID_PRICE', 70);
define('CURRENT_YEAR', date('Y'));

define('APP_ENV', $_ENV['APP_ENV']);
define('IS_DEBUG', $_ENV['IS_DEBUG']);
define('IS_DEV', APP_ENV === 'dev');

ini_set('display_errors', IS_DEV ? '1' : '0');
error_reporting(IS_DEV ? E_ALL : 0);
