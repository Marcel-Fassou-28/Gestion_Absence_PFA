<?php

namespace App\Abstract;

abstract class Table {
    protected $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}