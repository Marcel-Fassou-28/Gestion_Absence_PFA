<?php

use App\Admin\adminTable;
use PHPUnit\Framework\TestCase;
use App\Connection;
use App\Model\Etudiant;
use App\Model\Utils\Etudiant\UtilisateurEtudiant;

class AdminTableTest extends TestCase {
    public function DB() {
        return new adminTable(Connection::getPDO());
    }

    public function testgetAll() {
        $this->assertIsArray($this->DB()->getAll('etudiant', Etudiant::class , 20, 20));
    }

    public function testgetStudentInfoByCIN() {
        $this->assertInstanceOf(UtilisateurEtudiant::class ,$this->DB()->getStudentInfoByCIN('BJ8478559'));
        $this->assertNull($this->DB()->getStudentInfoByCIN('BJ847h8559'));
    }
}