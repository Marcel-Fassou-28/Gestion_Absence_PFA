<?php

use App\UserTable;
use App\Connection;
use App\Model\Utilisateur;
use PHPUnit\Framework\TestCase;

class UserTableTest extends TestCase {

    public function testfindByUsername() {
        $table = new UserTable(Connection::getPDO());
        $this->assertInstanceOf(Utilisateur::class, $table->findByUsername('O00790130.haba'));
        $this->assertNull($table->findByUsername('ghjkfl'));
    }

    public function testfindByEmail() {
        $table = new UserTable(Connection::getPDO());
        $this->assertInstanceOf(Utilisateur::class, $table->findByEmail('marcelfassouhaba2003@gmail.com'));
        $this->assertNull($table->findByEmail('ghjkfl'));
    }

    public function testgetIdentification() {
        $table = new UserTable(Connection::getPDO());
        $this->assertInstanceOf(Utilisateur::class, $table->getIdentification('O00790130'));
        $this->assertNull($table->getIdentification('O0079O007901300130'));
    }

    public function testgetAllEtudiants() {
        $table = new UserTable(Connection::getPDO());
        $this->assertIsArray($table->getAllEtudiants());
        $this->assertIsArray($table->getAllAdmins());
    }


    public function testfindByCin() {
        $table = new UserTable(Connection::getPDO());
        $this->assertInstanceOf(Utilisateur::class, $table->findByCin('O00790130'));
        $this->assertNull($table->findByCin('ghjkfl'));
    }
    
}