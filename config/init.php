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
     * @return mixed
     */
    public static function start()
    {
        $superGlobals = new Superglobals;

        $requestScheme = $superGlobals->get_SERVER('REQUEST_SCHEME');

        $serverName = $superGlobals->get_SERVER('SERVER_NAME');

        $scriptName = $superGlobals->get_SERVER('SCRIPT_NAME');

        // Define site
        define('SITE_NAME', 'Over_Code');

        define('SITE_NAME_ASCII_ART', $a = preg_replace('~ ~', '&nbsp;', '
        _       _      ____       _            _                 _              _            _            _<br/>
        /\ \   /\ \    / / /\     /\ \         /\ \              /\ \           /\ \         /\ \         /\ \<br/>
       /  \ \  \ \ \  /_/ / /    /  \ \       /  \ \            /  \ \         /  \ \       /  \ \____   /  \ \<br/>
      / /\ \ \  \ \ \ \___\/    / /\ \ \     / /\ \ \          / /\ \ \       / /\ \ \     / /\ \_____\ / /\ \ \<br/>
     / / /\ \ \ / / /  \ \ \   / / /\ \_\   / / /\ \_\        / / /\ \ \     / / /\ \ \   / / /\/___  // / /\ \_\<br/>
    / / /  \ \_\\ \ \   \_\ \ / /_/_ \/_/  / / /_/ / /       / / /  \ \_\   / / /  \ \_\ / / /   / / // /_/_ \/_/<br/>
   / / /   / / / \ \ \  / / // /____/\    / / /__\/ /       / / /    \/_/  / / /   / / // / /   / / // /____/\<br/>
  / / /   / / /   \ \ \/ / // /\____\/   / / /_____/       / / /          / / /   / / // / /   / / // /\____\/<br/>
 / / /___/ / /     \ \ \/ // / /______  / / /\ \ \        / / /________  / / /___/ / / \ \ \__/ / // / /______<br/>
/ / /____\/ /       \ \  // / /_______\/ / /  \ \ \      / / /_________\/ / /____\/ /   \ \___\/ // / /_______\<br/>
\/_________/         \_\/ \/__________/\/_/    \_\/      \/____________/\/_________/     \/_____/ \/__________/<br/>'));

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
        session_start();
    }
}