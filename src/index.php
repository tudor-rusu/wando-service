<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);

use app\components\Application;

session_start();

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php');

$env_array = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . '.env');
if ($env_array['COMPONENTS'] && strlen($env_array['COMPONENTS']) > 0) {
    $env_array['COMPONENTS'] = json_decode($env_array['COMPONENTS'], true);
}

$app = Application::getInstance($env_array);
try {
    $app->run();
} catch (Exception $e) {
    print_r($e->getMessage());
}