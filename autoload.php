<?php
namespace P5;

class Autoloader
{
    static function call()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    static function autoload($class)
    {
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        $class = str_replace('\\', '/', $class);

        $path = ROOT . '/' . $class . '.php';

        if(file_exists($path)) {
            require_once($path);
        }
    }
}