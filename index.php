<?php

declare(strict_types=1);

use Over_Code\Config\Init;
use Over_Code\Config\Router;
use Over_Code\Config\Autoloader;

// Define constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DS', __DIR__ . DS);
define('CONFIG_PATH', ROOT_DS . 'config' . DS);

// Load application configuration
require_once CONFIG_PATH . 'autoloader.php';
require_once CONFIG_PATH . 'init.php';
require_once ROOT_DS . 'vendor' . DS . 'autoload.php';
require_once CONFIG_PATH . 'thirdParty.php';
//var_dump(SITE_ADRESS);exit;
// Start own services
Autoloader::start();
Init::start();
Router::start();
