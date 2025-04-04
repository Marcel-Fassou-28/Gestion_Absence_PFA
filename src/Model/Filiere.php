<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser une Filière
 * 
 * @property int $idFiliere
 * @property string $nomFiliere
 * @property int $idDepartement
 */
class Filiere {

    /**
     * @var int $idFiliere
     */
    protected $idFiliere;

    /**
     * @var string $nomFiliere
     */
    protected $nomFiliere;

    /**
     * @var int $idDepartement
     */
    protected $idDepartement;

    /*public function __construct($idFiliere, $nomFiliere, $idDepartement)
    {
        $this->idFiliere = $idFiliere;
        $this->nomFiliere = $nomFiliere;
        $this->idDepartement = $idDepartement;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDFiliere() : ?int {
        return $this->idFiliere;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getNomFiliere() : ?string {
        return $this->nomFiliere;
    }


    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDDepartement() : ?int {
        return $this->idDepartement;
    }
    
    /**
     * Setters
     * 
     * @param int $idFiliere
     */
    public function setIDFiliere(int $idFiliere) {
        $this->idFiliere = $idFiliere;
    }

    /**
     * Setters
     * 
     * @param string $nomFiliere
     */
    public function setNomFiliere(string $nomFiliere) {
        $this->nomFiliere = $nomFiliere;
    }

    /**
     * Setters
     * 
     * @param int $idDepartement
     */
    public function setDepartement(string $idDepartement) {
        $this->idDepartement = $idDepartement;
    }

}