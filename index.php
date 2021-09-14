<?php

USE P5\Config\Init;
USE P5\Config\Router;
USE P5\Config\Autoloader;
USE Symfony\Component\Dotenv\Dotenv;

// Define needed constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DS', __DIR__ . DS);


// Load application configuration
require_once(ROOT_DS . 'config' . DS .'autoloader.php');
require_once(ROOT_DS . 'config'. DS . 'init.php');
require_once(ROOT_DS . 'vendor' . DS . 'autoload.php');

// Start own services
Autoloader::run();
Init::run();
Router::run();

//Start third party services
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$loader = new \Twig\Loader\FilesystemLoader(ROOT_DS . 'views' . DS . 'client');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);