<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser un Niveau
 * 
 * @property int $idDepartement
 * @property string $nomDepartement
 */
class Departement {

    /**
     * @var int $idDepartement
     */
    private $idDepartement;

    /**
     * @var string $nomDepartement
     */
    private $nomDepartement;

    /*public function __construct($idDepartement, $nomDepartement)
    {
        $this->idDepartement = $idDepartement;
        $this->nomDepartement = $nomDepartement;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDNiveau() : ?int {
        return $this->idDepartement;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomDepartement() : ?string {
        return $this->nomDepartement;
    }
    
    /**
     * Setters
     * 
     * @param int $idDepartement
     */
    public function setIDNiveau(int $idDepartement) {
        $this->idDepartement = $idDepartement;
    }

    /**
     * Setters
     * 
     * @param string $nomDepartement
     */
    public function setNomNiveau(string $nomDepartement) {
        $this->nomDepartement = $nomDepartement;
    }

}