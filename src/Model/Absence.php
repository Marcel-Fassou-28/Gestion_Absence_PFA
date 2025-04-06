<?php

namespace App\Model;
use DateTime;

/**
 * Cette classe est definie pour caractériser une absence
 * 
 * @property int $idAbsence
 * @property \DateTime $date
 * @property string $cinEtudiant
 * @property int $idMatiere
 */
class Absence {

    /**
     * @var int $idAbsence
     */
    protected $idAbsence;

    /**
     * @var \DateTime $date
     */
    protected $date;

    /**
     * @var string $cinEtudiant
     */
    protected $cinEtudiant;

    /**
     * @var int $idMatiere
     */
    protected $idMatiere;

    /*public function __construct($idAbsence, $date, $cinEtudiant, $idMatiere)
    {
        $this->idAbsence = $idAbsence;
        $this->date = $date;
        $this->cinEtudiant = $cinEtudiant;
        $this->idMatiere = $idMatiere;
    }*/

    /**
     * Getters
     * 
     * @return int $idAbsence
     */
    public function getIDAbsence() :?int {
        return $this->idAbsence;
    }

    /**
     * Getters
     * 
     * @return DateTime $date
     */
    public function getDate() :?DateTime {
        return $this->date;
    }

    /**
     * Getters
     * 
     * @return string $cinEtudiant
     */
    public function getCINEtudiant() :?string {
        return $this->cinEtudiant;
    }
    
    /**
     * Getters
     * 
     * @return int $idMatiere
     */
    public function getIDMatiere() :?int {
        return $this->idMatiere;
    }

    /**
     * Setters
     * 
     * @param int $idMatiere
     */
    public function setIDMatiere(int $idMatiere) {
        $this->idMatiere = $idMatiere;
    }

    /**
     * Setters
     * 
     * @param int $idAbsence
     */
    public function setIDAbsence(int $idAbsence) {
        $this->idAbsence = $idAbsence;
    }

    /**
     * Setters
     * 
     * @param dateTime $date
     */
    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    /**
     * Setters
     * 
     * @param string $cinEtudiant
     */
    public function setIDEtudiant(string $cinEtudiant) {
        $this->cinEtudiant = $cinEtudiant;
    }
    
}