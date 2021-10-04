<?php

namespace Over_Code\Models;

use PDO;
use Over_Code\Db\DbConnect;

abstract class MainModel
{
    protected $pdo;
    
    /**
     * Main manager
     */
    public function __construct()
    {
        $this->pdo = new DbConnect;
    }
}