<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser un Niveau
 * 
 * @property int $idNiveau
 * @property string $nomNiveau
 */
class Niveau {

    /**
     * @var int $idFiliere
     */
    protected $idNiveau;

    /**
     * @var string $nomNiveau
     */
    protected $nomNiveau;

    /*public function __construct($idNiveau, $nomNiveau)
    {
        $this->idNiveau = $idNiveau;
        $this->nomNiveau = $nomNiveau;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDNiveau() : ?int {
        return $this->idNiveau;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getNomNiveau() : ?string {
        return $this->nomNiveau;
    }
    
    /**
     * Setters
     * 
     * @param int $idNiveau
     */
    public function setIDNiveau(int $idNiveau) {
        $this->idNiveau = $idNiveau;
    }

    /**
     * Setters
     * 
     * @param string $nomAnnee
     */
    public function setNomNiveau(string $nomNiveau) {
        $this->nomNiveau = $nomNiveau;
    }

}