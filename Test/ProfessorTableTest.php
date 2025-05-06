<?php

use App\Professeur\ProfessorTable;
use PHPUnit\Framework\TestCase;
use App\Connection;

class ProfessorTableTest extends TestCase {

    public function testgetAllStudentAbsenceState() {
        $table = new ProfessorTable(Connection::getPDO());
        $this->assertIsArray($table->getAllStudentAbsenceState('O00790130', 20, 20));
        $this->assertIsArray($table->getAllStudentAbsenceState('O00790130c', 20, 20));
    }

    public function testgetClasse() {
        $table = new ProfessorTable(Connection::getPDO());
        $this->assertIsArray($table->getClasse('O00790130'));
        $this->assertIsArray($table->getClasse('O00790130c'));
    }

    public function testgetMatiere() {
        $table = new ProfessorTable(Connection::getPDO());
        $this->assertIsArray($table->getMatiere('O00790130'));
        $this->assertIsArray($table->getMatiere('O00790130c'));
    }

    public function testgetFiliere() {
        $table = new ProfessorTable(Connection::getPDO());
        $this->assertIsArray($table->getFiliere('O00790130'));
        $this->assertIsArray($table->getFiliere('O00790130c'));
    }
}