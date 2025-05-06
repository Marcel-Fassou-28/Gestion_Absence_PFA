<?php

use App\ClasseTable;
use PHPUnit\Framework\TestCase;
use App\Connection;
use App\Model\Classe;

class ClasseTableTest extends TestCase {

    public function testfindById() {
        $table = new ClasseTable(Connection::getPDO());
        $this->assertInstanceOf(Classe::class, $table->findById(1));
        $this->assertNull($table->findById(50));
    }
}
