<?php

namespace P5\Config;

use P5\Libraries\Helpers;
use P5\Libraries\Superglobals;

/**
 * Instantiate the controller class , based on $_GET['route']
 * and call display method of this controller
 */
class Router
{
    /**
     * Run the routing
     *
     * @return object $controller for displaying twig file
     */
    public static function start()
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

        $action = (file_exists('controllers' . DS . $hub . DS . $action . 'Controller.php')) ? $action : 'pageNotFound';
        
        $template = $hub . DS . $action . '.twig';

        // Loads the template and renders it
        
        $controller = '\\P5\\Controllers\\' . ucfirst($hub) . '\\' . ucfirst($action) . 'Controller';

        $controller = new $controller($template, $params);

        return $controller->display();
    }
}
