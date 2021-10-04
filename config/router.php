<?php

namespace Over_Code\Config;

use Over_Code\Libraries\Helpers;
use Over_Code\Libraries\Superglobals;

/**
 * Instantiate the controller class , based on $_GET['route']
 * and call display method of this controller
 */
class Router
{
    /**
     * Run the routing
     *
     * @return mixed
     */
    public static function start(): mixed
    {
        $superGlobals = new Superglobals;

        $route = $superGlobals->get_GET('route');

        $hub = $superGlobals->get_SESSION('hub');

        // Define variables
        //$route = (isset($_GET['route'])) ? filter_var($_GET['route'], FILTER_SANITIZE_URL) : '';
        $route = strip_tags($route);

        $params = explode('/', $route);

        $action = array_shift($params);

        $action = ($route === '') ? 'accueil' : lcfirst($action);

        $hub = ($hub === 'admin') ? 'admin' : 'client';

        $action = (is_file('controllers' . DS . $hub . DS . $action . 'Controller.php')) ? $action : 'pageNotFound';
        
        $template = $hub . DS . $action . '.twig';

        // Loads the template and renders it
        
        $controller = '\\Over_Code\\Controllers\\' . ucfirst($hub) . '\\' . ucfirst($action) . 'Controller';

        $controller = new $controller($template, $params);

        return $controller->display();
    }
}
