<?php

use P5\Autoloader;

// Root folder project definition
// ------------------------------
define ('ROOT', __DIR__);

// Autoloder
// ---------
require_once('autoload.php');
Autoloader::call();

// Twig autoload
// -------------
require_once('vendor/autoload.php');