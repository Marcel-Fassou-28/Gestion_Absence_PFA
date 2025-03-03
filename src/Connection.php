<?php

namespace App;
use \PDO;

class Connection {
    public static function getPDO():PDO {
        $dsn = "mysql:host=localhost;dbname=gaensaj;charset=utf8mb4";
        $username = "root";
        $password = "";
        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}