<?php 

namespace P5\Config;

/**
 * Instantiate the controller class and call method based on $_GET['route']
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
        $route = (isset($_GET['route'])) ? htmlspecialchars($_GET['route']) : '';

        $params = explode('/', $route);

        $action = array_shift($params);        

        $action = ($route === '') ? 'accueil' : lcfirst($action);

        $hub = (isset($_SESSION['hub']) && $_SESSION['hub'] === 'admin') ? 'admin' : 'client';
        
        $file = 'controllers' . DS . $hub . DS . $action . 'Controller.php';

        $template = $hub . DS . $action . '.twig';

        // File control
        if (!file_exists($file)) {
            header('HTTP/1.0 404 Not Found');
            exit('code 404');
        }

        // Loads the template and renders it
        $controller = '\\P5\\Controllers\\' . ucfirst($hub) . '\\' . $action . 'Controller';

        $controller = new $controller($template, $params);

        return $controller->display();
    }
}