<?php

namespace Over_Code\Db;

use PDO;
//use Exception;

/**
 * Manage connection to database
 */
class DbConnect extends PDO
{
    /**
     * Construct magic method: return a PDO instance
     */
    public function __construct()
    {
        /*
        try{

            $pdo = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'pass');

            return $pdo;

        }catch(Exception $e){

            die('Erreur de connection : ' . $e->getMessage());

        }
        */
        parent::__construct('mysql:host=localhost;dbname=overtest', 'root', 'pass');  
    }
}