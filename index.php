<?php


// Define root path

use P5\Core\MainController;
use P5\Controllers\Admin\Controller as AdminController;
use P5\Controllers\Home\Controller as HomeController;

define('ROOT', __DIR__);

define('DS', DIRECTORY_SEPARATOR);

define('ROOT_DS', ROOT . DS);

// Load application configuration
require_once(ROOT_DS . 'vendor' . DS . 'autoload.php');

require_once(ROOT_DS . 'config'. DS . 'config.php');

P5\Config\Config::run();

//require_once('core/mainController.php');
$test1 = new MainController();
echo '<br/>';
$test2 = new AdminController();
echo '<br/>';
$test3 = new HomeController();