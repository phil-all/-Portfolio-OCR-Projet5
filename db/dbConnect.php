<?php

namespace Over_Code\Db;

use PDO;
use Exception;

/**
 * Manage connection to database
 */
class DbConnect// extends PDO
{
    use \Over_Code\Libraries\Helpers;

    private string $dsn;
    private string $user;
    private string $pass;
    private $pdo;

    /**
     * Initialise and set PDO construct parameters needed
     */
    public function __construct()
    {
        $this->dsn = self::get_Env('DSN');
        $this->user = self::get_Env('DB_USERNAME');
        $this->pass = self::get_Env('DB_PASSWORD');
    }
    
    /**
     * Return a pdo object
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
 
        try
        {
            return $this->pdo ?? $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $pdo_options);
        }
        catch(Exception $e)
        {
            return "Erreur de connexion :" . $e->getMessage();
        }
        
    }
}