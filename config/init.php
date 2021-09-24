<?php

namespace P5\Config;

/**
 * Define constants & Start session
 */
class Init
{
    /**
     * Run initialisation
     *
     * @return void
     */
    public static function start(): void
    {
        // Define site
        define('SITE_NAME', 'OverCode');

        define('SITE_ADRESS' , $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']));
        
        // Define path constants
        define('CONTROLLERS_PATH', ROOT_DS . 'controllers' . DS);

        define('ADMIN_CONTROLLERS', CONTROLLERS_PATH . 'admin' . DS);

        define('CLIENT_CONTROLLERS', CONTROLLERS_PATH . 'client' . DS);

        define('DB_PATH', ROOT_DS . 'db' . DS);

        define('LIB_PATH', ROOT_DS . 'libraries' . DS);

        define('MODELS_PATH', ROOT_DS . 'models' . DS);

        define('PUBLIC_PATH', ROOT_DS . 'public' . DS);

        define('CSS_PATH', PUBLIC_PATH . 'css' . DS);

        define('JS_PATH', PUBLIC_PATH . 'js' . DS);

        define('IMAGES_PATH', PUBLIC_PATH . 'images' . DS);

        define('UPLOADS_PATH', PUBLIC_PATH . 'uploads' . DS);

        define('VIEWS_PATH', ROOT_DS . 'views' . DS);

        // Start session
        session_start();
    }
}