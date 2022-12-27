<?php

class Database {

    public static function connection()
    {
        $dns = 'mysql:dbname=cafeteria;host=localhost';
        $user = 'root';
        $password = '';

        try {
            $connection = new PDO($dns, $user, $password);
            $connection->exec("set names utf8");
            
            return $connection;

        } catch (PDOException $err) {
           
            die('Error'. $err->getMessage());
        }

    }
}