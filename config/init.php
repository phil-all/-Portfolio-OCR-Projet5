<?php

namespace Over_Code\Config;

use Over_Code\Libraries\Superglobals;

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
        $superGlobals = new Superglobals;

        $requestScheme = $superGlobals->get_SERVER('REQUEST_SCHEME');

        $serverName = $superGlobals->get_SERVER('SERVER_NAME');

        $scriptName = $superGlobals->get_SERVER('SCRIPT_NAME');

        // Define site
        define('SITE_NAME', 'Over_Code');

        define('SITE_ADRESS' , $requestScheme . '://' . $serverName . dirname($scriptName));
        
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}