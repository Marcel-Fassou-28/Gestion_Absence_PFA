<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser une Filière
 * 
 * @property int $idFiliere
 * @property string $nomFiliere
 * @property string $alias
 * @property int $idDepartement
 */
class Filiere {

    /**
     * @var int $idFiliere
     */
    private $idFiliere;

    /**
     * @var string $nomFiliere
     */
    private $nomFiliere;

    /**
     * @var string $alias
     */
    private $alias;

    /**
     * @var int $idDepartement
     */
    private $idDepartement;

    /*public function __construct($idFiliere, $nomFiliere, $alias, $idDepartement)
    {
        $this->idFiliere = $idFiliere;
        $this->nomFiliere = $nomFiliere;
        $this->alias = $alias;
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
     * @return string|null
     */
    public function getNomFiliere() : ?string {
        return $this->nomFiliere;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getAlias() : ?string {
        return $this->alias;
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
     * @param string $alias
     */
    public function setAlias(string $alias) {
        $this->alias = $alias;
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