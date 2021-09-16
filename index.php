<?php

use P5\Config\Init;
use P5\Config\Router;
use P5\Config\Autoloader;
use Symfony\Component\Dotenv\Dotenv;

// Define needed constants
//define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DS', __DIR__ . DS);


// Load application configuration
require_once(ROOT_DS . 'config' . DS .'autoloader.php');
require_once(ROOT_DS . 'config'. DS . 'init.php');
require_once(ROOT_DS . 'vendor' . DS . 'autoload.php');

//Start third party services
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$loader = new \Twig\Loader\FilesystemLoader(ROOT_DS . 'views' . DS . 'client');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);
//https://www.youtube.com/watch?v=ywEmvPXfZnY

// Start own services
Autoloader::run();
Init::run();
Router::run();
//$template = Router::run();
//var_dump(Router::run());

//echo $twig->render(Router::run());