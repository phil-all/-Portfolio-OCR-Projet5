<?php

Class MainModel
{
    public static function getPdo(): PDO
    {
        $db = new PDO('mysql:host=localhost;dbname=philblog;charset=utf8', 'root', '');

        return $db;
    }
}