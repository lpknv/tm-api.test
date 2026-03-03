<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('APP_ENV', $_ENV['APP_ENV']);
define('IS_DEV', APP_ENV === 'dev');
define('MAX_KIDS_NUMBER', 5);