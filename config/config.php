<?php

class Config
{
    public static function run()
    {
        self::init();

        self::autoload();

        self::routing();
    }

    private static function init()
    {
        // constants definition
        define('DS', DIRECTORY_SEPARATOR);

        define('PUBLIC_PATH', ROOT .  DS . 'public' . DS);

        define('CSS_PATH', PUBLIC_PATH . 'css' . DS);

        define('JS_PATH', PUBLIC_PATH . 'js' . DS);

        define('IMAGES_PATH', PUBLIC_PATH . 'images' . DS);

        define('UPLOADS_PATH', PUBLIC_PATH . 'uploads' . DS);

        // session starting
        session_start();
    }

    private static function autoload()
    {

    }

    private static function routing()
    {

    }

}