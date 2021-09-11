<?php

// Define necessary constants
define('DS', DIRECTORY_SEPARATOR);

define('ROOT_DS', __DIR__ . DS);


// Load application configuration
require_once(ROOT_DS . 'vendor' . DS . 'autoload.php');

require_once(ROOT_DS . 'config'. DS . 'config.php');

P5\Config\Config::run();

P5\Core\MainController::run();