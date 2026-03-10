<?php

session_start();

include __DIR__ . '/php-csrf.php';

$csrf = new CSRF();
