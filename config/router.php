<?php

namespace P5\Config;

use P5\Libraries\Helpers;

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
        // Define variables
        //$route = (isset($_GET['route'])) ? filter_var($_GET['route'], FILTER_SANITIZE_URL) : '';
        $route = (isset($_GET['route'])) ? htmlentities($_GET['route']) : '';

        $params = explode('/', $route);

        $action = array_shift($params);

        $action = ($route === '') ? 'accueil' : lcfirst($action);

        $hub = (isset($_SESSION['hub']) && $_SESSION['hub'] === 'admin') ? 'admin' : 'client';

        $action = (file_exists('controllers' . DS . $hub . DS . $action . 'Controller.php')) ? $action : 'pageNotFound';
        
        $template = $hub . DS . $action . '.twig';

        // Loads the template and renders it
        
        $controller = '\\P5\\Controllers\\' . ucfirst($hub) . '\\' . ucfirst($action) . 'Controller';

        $controller = new $controller($template, $params);

        return $controller->display();
    }
}
