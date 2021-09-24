<?php

namespace P5\Models;

use P5\Db\DbConnect;

abstract class MainModel
{
    protected $pdo;
    
    /**
     * Construct magic method: instantiate db connection
     */
    public function __construct()
    {
        $this->pdo = new DbConnect;
    }
}