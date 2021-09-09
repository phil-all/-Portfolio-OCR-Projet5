<?php

// Root site definition
define('ROOT', __DIR__);

// Load application configuration
require_once(ROOT . '/config/config.php');

Config::run();

require_once(ROOT . 'vendor/autoload.php');