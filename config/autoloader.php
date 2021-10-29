<?php

namespace Over_Code\Config;

/**
 * Autoloader class
 */
class Autoloader
{
    /**
     * Run the autoload register
     * 
     * @return void
     */
    static function start(): void
    {
        spl_autoload_register(array(
            __CLASS__,
            'load'
        ));
    }

    /**
     * Require called class file
     *
     * @param string $class called class with namespace, ex: Over_Code\Folder\Sub-folder\ClassName
     * 
     * @return void
     */
    public static function load(string $class): void
    {
        $array = array_map('lcfirst', explode('\\', $class));

        unset($array[0]);

        $class = implode('/', $array);

        if (is_file(ROOT_DS . $class . '.php')) {
            $classPath = ROOT_DS . $class . '.php';
            require_once $classPath;
        }
    }
}
