<?php

USE Symfony\Component\Dotenv\Dotenv;
USE P5\Config\Config;
USE P5\Core\MainController;

// Define necessary constants
define('DS', DIRECTORY_SEPARATOR);

define('ROOT_DS', __DIR__ . DS);


// Load application configuration
require_once(ROOT_DS . 'vendor' . DS . 'autoload.php');

require_once(ROOT_DS . 'config'. DS . 'config.php');

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

Config::run();

MainController::run();