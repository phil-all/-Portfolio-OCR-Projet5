<?php
// Instantiate the controller class and call method based on URL

namespace P5\Core;

class MainController
{
    public static function run()
    {
        // visiteurs et membres
        require_once(CONTROLLERS_PATH . 'client' . DS . 'controller.php');

        //admin
        require_once(CONTROLLERS_PATH . 'admin' . DS . 'controller.php');
    }
    
}



// si ROOT/index.php on renvoie vers le controlleur homme/controlleur
//var_dump($url);