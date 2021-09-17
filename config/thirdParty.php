<?php

// Dotenv config
$dotenv = new Symfony\Component\Dotenv\Dotenv();

$dotenv->load(dirname(__DIR__).'/.env');


// Twig config
$loader = new \Twig\Loader\FilesystemLoader(ROOT_DS . 'views' . DS . 'client');

$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);