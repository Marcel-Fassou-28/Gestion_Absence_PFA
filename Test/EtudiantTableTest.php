<?php

use App\EtudiantTable;
use PHPUnit\Framework\TestCase;
use App\Connection;
use App\Model\Etudiant;
use App\Model\Filiere;
use App\Model\Utils\Etudiant\DerniereAbsenceEtudiant;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNull;

class EtudiantTableTest extends TestCase {

    public function testgetFiliere() {
        $table = new EtudiantTable(Connection::getPDO());
        assertInstanceOf(Filiere::class, $table->getFiliere('CI00000001'));
        assertNull($table->getFiliere('ddgd'));
    }

    public function testgetInfoGeneralEtudiant() {
        $table = new EtudiantTable(Connection::getPDO());
        assertInstanceOf(DerniereAbsenceEtudiant::class, $table->getInfoGeneralEtudiant('BJ8478559'));
        assertNull($table->getInfoGeneralEtudiant('ddgd'));
    }

    public function testgetInfoGeneralEtudiantWithoutLastAbsence() {
        $table = new EtudiantTable(Connection::getPDO());
        assertInstanceOf(DerniereAbsenceEtudiant::class, $table->getInfoGeneralEtudiantWithoutLastAbsence('BJ8478559'));
        assertNull($table->getInfoGeneralEtudiantWithoutLastAbsence('ddgd'));
    }

    public function testgetStatistiqueAbsenceEtudiant() {
        $table = new EtudiantTable(Connection::getPDO());
        assertIsArray($table->getStatistiqueAbsenceEtudiant('BJ8478559'));
        assertEmpty($table->getStatistiqueAbsenceEtudiant('ddgd'));
    }

    public function testfindByCin() {
        $table = new EtudiantTable(Connection::getPDO());
        assertInstanceOf(Etudiant::class ,$table->findByCin('BJ8478559'));
        assertEmpty($table->findByCin('ddgd'));
        assertNull($table->findByCin('ddgd'));
    }

    public function testgetAllByClasse() {
        $table = new EtudiantTable(Connection::getPDO());
        assertIsArray($table->getAllByClasse('852'));
        assertIsArray($table->getAllByClasse(1));
        assertEmpty($table->getAllByClasse('155'));
    }
}