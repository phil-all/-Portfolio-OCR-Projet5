<?php

namespace Over_Code\Db;

use PDO;
//use Exception;

/**
 * Manage connection to database
 */
class DbConnect extends PDO
{
    use \Over_Code\Libraries\Helpers;

    private string $ds;
    private string $user;
    private string $pass;

    /**
     * Return a PDO instance
     */
    public function __construct()
    {
        $this->dsn = self::get_Env('DSN');
        $this->user = self::get_Env('DB_USERNAME');
        $this->pass = self::get_Env('DB_PASSWORD');
        
        parent::__construct($this->dsn, $this->user, $this->pass);
    }
}