<?php

use App\AbsenceTable;
use PHPUnit\Framework\TestCase;
use App\Connection;

class AbsenceTableTest extends TestCase {
    
    public function testgetAbsencesNonJustifiees() {
        $table = new AbsenceTable(Connection::getPDO());
        $this->assertIsArray($table->getAbsencesNonJustifiees('BJ8478559'));
        $this->assertIsNotArray($table->getAbsencesNonJustifiees('BJ847855d'));
        $this->assertEmpty($table->getAbsencesNonJustifiees('BJ8478559'));
        $this->assertEmpty($table->getAbsencesNonJustifiees('BJ84785d59'));
    }
}