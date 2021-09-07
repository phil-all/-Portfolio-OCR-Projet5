<?php

// Root folder project definition
// ------------------------------
define ('ROOT', __DIR__);

// Autoloder
// ---------
require_once(ROOT . '/core/autoload.php');

Autoloader::call();


// Twig autoload
// -------------
require_once(ROOT . '/vendor/autoload.php');