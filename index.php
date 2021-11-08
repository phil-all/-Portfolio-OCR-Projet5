<?php

declare(strict_types=1);

use Over_Code\Config\Init;
use Over_Code\Config\Router;
use Over_Code\Config\Autoloader;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DS', __DIR__ . DS);
define('CONFIG_PATH', ROOT_DS . 'config' . DS);

require_once CONFIG_PATH . 'autoloader.php';
Autoloader::start();

require_once CONFIG_PATH . 'init.php';
Init::start();

require_once ROOT_DS . 'vendor' . DS . 'autoload.php';

$router = new Router();
