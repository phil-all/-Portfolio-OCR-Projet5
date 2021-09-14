<?php
// Instantiate the controller class and call method based on URL

namespace P5\Config;



class Router
{
    public static function run()
    {
        // Define variables
        $route = $_GET['route']; // Due to rewrite rule in .htacces : $_GET['route'] don't need isset

        $routeInArray = explode('/', $route);

        $exitFirst = array_shift($routeInArray);

        $hub = (isset($_SESSION['hub']) && $_SESSION['hub'] === 'admin') ? 'Admin' : 'Client';

        $classController = ($route === '') ? 'accueil' : lcfirst($exitFirst);
        
        $file = 'controllers' . DS . lcfirst($hub) . DS . $classController . 'Controller.php';
        
        // Control
        if (!file_exists($file)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $controller = '\\P5\\Controllers\\' . $hub . '\\' . $classController . 'Controller';

        $controller::run();
    }
}