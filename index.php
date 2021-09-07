<?php

// Root folder project definition
// ------------------------------
define ('ROOT', __DIR__);


// Autoloder
// ---------
use P5\Autoloader;

require_once('autoload.php');

Autoloader::call();


// Twig autoload
// -------------
require_once('vendor/autoload.php');