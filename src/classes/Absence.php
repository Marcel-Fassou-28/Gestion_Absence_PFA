<?php

namespace App;
use DateTime;

/**
 * Cette classe est definie pour caractériser une absence
 * 
 * @property int $idAbsence
 * @property \DateTime $date
 * @property \DateTime $horaire
 * @property int $idEtudiant
 * @property int $idMatiere
 */
class Absence {

    /**
     * @var int $idAbsence
     */
    private $idAbsence;

    /**
     * @var \DateTime $date
     */
    private $date;

    /**
     * @var \DateTime $horaire
     */
    private $horaire;

    /**
     * @var int $idEtudiant
     */
    private $idEtudiant;

    /**
     * @var int $idMatiere
     */
    private $idMatiere;

    public function __construct($idAbsence, $date, $horaire, $idEtudiant, $idMatiere)
    {
        $this->idAbsence = $idAbsence;
        $this->date = $date;
        $this->horaire = $horaire;
        $this->idEtudiant = $idEtudiant;
        $this->idMatiere = $idMatiere;
    }

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
     * @return int $idEtudiant
     */
    public function getIDEtudiant() :?int {
        return $this->idEtudiant;
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
     * Getters
     * 
     * @return DateTime $horaire
     */
    public function getHoraire() :?DateTime {
        return $this->horaire;
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
     * @param DateTime $horaire
     */
    public function setHoraire(DateTime $horaire) {
        $this->horaire = $horaire;
    }

    /**
     * Setters
     * 
     * @param int $idEtudiant
     */
    public function setIDEtudiant(int $idEtudiant) {
        $this->idEtudiant = $idEtudiant;
    }
    
}