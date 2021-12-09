<?php

namespace Over_Code\Db;

use PDO;
use Exception;

/**
 * Manage connection to database
 */
class DbConnect
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
        $this->dsn  = self::getEnv('DSN');
        $this->user = self::getEnv('DB_USERNAME');
        $this->pass = self::getEnv('DB_PASSWORD');
    }
    
    /**
     * Return a pdo object
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        $pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        
        $pdoOptions[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
 
        try {
            return $this->pdo ?? $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $pdoOptions);
        } catch (Exception $e) {
            return "Erreur de connexion :" . $e->getMessage();
        }
    }
}
