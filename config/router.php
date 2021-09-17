<?php 

namespace P5\Config;

use P5\Controllers\MainController;
/**
 * Instantiate the controller class and call method based on URL
 */
class Router
{
    /**
     * Run the routing
     *
     * @return object $controller for displaying twig file
     */
    public static function run()
    {
        // Define Class Routing Variables
        $route = $_GET['route']; // Due to rewrite rule in .htacces : $_GET['route'] don't need isset

        $routeInArray = explode('/', $route);

        $exitFirst = array_shift($routeInArray);

        $hub = (isset($_SESSION['hub']) && $_SESSION['hub'] === 'admin') ? 'Admin' : 'Client';

        $classAction = ($route === '') ? 'accueil' : lcfirst($exitFirst);
        
        $file = 'controllers' . DS . lcfirst($hub) . DS . $classAction . 'Controller.php';

        // Define Twig Routing Variables
        // action(template, ex accueil) + parametres array( hub, param1, param 2)
        $action = lcfirst($hub) . DS . $classAction . '.twig';
        $params = $routeInArray; // route in array after shifting class action

        //var_dump($routeInArray);die;

        
        // File Control
        if (!file_exists($file)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $controller = '\\P5\\Controllers\\' . $hub . '\\' . $classAction . 'Controller';

        $controller = new $controller($action, $params);

        return $controller->display();
    }
}