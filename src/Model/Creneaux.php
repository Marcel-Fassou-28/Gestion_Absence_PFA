<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser un Créneau
 * @property string $jourSemaine
 * @property string $heureDebut
 * @property string $heureFin
 * @property string $cinProf
 * @property int $idMatiere
 */
class Creneaux {

    /**
     * @var string $jourSemaine
     */
    private $jourSemaine;

    /**
     * @var string $heureDebut
     */
    private $heureDebut;

    /**
     * @var string $heureFin
     */
    private $heureFin;

    /**
     * @var string $cinProf
     */
    private $cinProf;

    /**
     * @var int $idMatiere
     */
    private $idMatiere;

    /*public function __construct(string $jourSemaine,string $heureDebut,string $heureFin,string $cinProf,int $idMatiere)
    {
        $this->jourSemaine = $jourSemaine;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->cinProf = $cinProf;
        $this->idMatiere = $idMatiere;
    }*/

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getJourSemaine() : ?string {
        return $this->jourSemaine;
    }

    /**
     * Setters
     * 
     * @param string|null
     */
    public function setJourSemaine(string $jourSemaine) {
        $this->jourSemaine = $jourSemaine;
    }


    /**
     * Getters
     * 
     * @return string|null
     */
    public function getHeureDebut() : ?string {
        return $this->heureDebut;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getHeureFin() : ?string {
        return $this->heureFin;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getCinProf() : ?string {
        return $this->cinProf;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdMatiere() : ?int {
        return $this->idMatiere;
    }
    
    /**
     * Setters
     * 
     * @param string $heureDebut
     */
    public function setHeureDebut(string $heureDebut) {
        $this->heureDebut = $heureDebut;
    }

    /**
     * Setters
     * 
     * @param string $heureFin
     */
    public function setHeureFin(string $heureFin) {
        $this->heureFin = $heureFin;
    }

    /**
     * Setters
     * 
     * @param string $cinProf
     */
    public function setCinProf(string $cinProf) {
        $this->cinProf = $cinProf;
    }

    /**
     * Setters
     * 
     * @param int $idMatiere
     */
    public function setIdMatiere(int $idMatiere) {
        $this->idMatiere = $idMatiere;
    }
}
