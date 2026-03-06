<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('APP_ENV', $_ENV['APP_ENV']);
define('IS_DEV', APP_ENV === 'dev');
define('MAX_KIDS_NUMBER', 5);
define('CURRENT_YEAR', date('Y'));
define('FIRST_KID_PRICE', 80);
define('NTH_KID_PRICE', 70);
define('IS_DEBUG', false);
