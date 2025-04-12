<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser un Créneau
 * 
 * @property string $heureDebut
 * @property string $heureFin
 * @property string $cinProf
 * @property int $idMatiere
 */
class Creneaux {

    /**
     * @var string $heureDebut
     */
    protected $heureDebut;

    /**
     * @var string $heureFin
     */
    protected $heureFin;

    /**
     * @var string $cinProf
     */
    protected $cinProf;

    /**
     * @var int $idMatiere
     */
    protected $idMatiere;

    /*public function __construct($heureDebut, $heureFin, $cinProf, $idMatiere)
    {
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
