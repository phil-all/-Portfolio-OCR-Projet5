<?php

namespace P5\Models;

use PDO;
use P5\Db\DbConnect;

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