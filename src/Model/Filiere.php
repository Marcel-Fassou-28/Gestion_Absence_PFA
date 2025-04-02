<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser une Filière
 * 
 * @property int $idFiliere
 * @property string $nomFiliere
 * @property string $niveau
 * @property string $departement
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
     * @var string $niveau
     */
    protected $niveau;

    /**
     * @var int $departement
     */
    protected $departement;

    /*public function __construct($idFiliere, $nomFiliere, $niveau, $departement)
    {
        $this->idFiliere = $idFiliere;
        $this->nomFiliere = $nomFiliere;
        $this->niveau = $niveau;
        $this->departement = $departement;
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
     * @return string|null
     */
    public function getNiveau() : ?string {
        return $this->niveau;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getDepartement() : ?string {
        return $this->departement;
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
     * @param string $niveau
     */
    public function setNiveau(string $niveau) {
        $this->niveau = $niveau;
    }

    /**
     * Setters
     * 
     * @param string $departement
     */
    public function setDepartement(string $departement) {
        $this->departement = $departement;
    }

}