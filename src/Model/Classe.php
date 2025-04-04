<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser un Niveau
 * 
 * @property int $idClasse
 * @property string $nomClasse
 * @property int $idNiveau
 * @property int $idFiliere
 */
class Classe {

    /**
     * @var int $idClasse
     */
    protected $idClasse;

    /**
     * @var string $nomClasse
     */
    protected $nomClasse;

    /**
     * @var int $idNiveau
     */
    protected $idNiveau;

    /**
     * @var int $idFiliere
     */
    protected $idFiliere;

    /*public function __construct($idClasse, $nomClasse, $idFiliere)
    {
        $this->idClasse = $idClasse;
        $this->nomClasse = $nomClasse;
        $this->idNiveau = $idNiveau;
        $this->idFiliere = $idFiliere;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDClasse() : ?int {
        return $this->idClasse;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getNomClasse() : ?string {
        return $this->nomClasse;
    }
    
    /**
     * Setters
     * 
     * @param int $idClasse
     */
    public function setIDClasse(int $idClasse) {
        $this->idClasse = $idClasse;
    }

    /**
     * Setters
     * 
     * @param string $nomClasse
     */
    public function setNomClasse(string $nomClasse) {
        $this->nomClasse = $nomClasse;
    }

    /**
     * Getters
     * 
     * @return int $idNiveau
     */
    public function getIdNiveau() {
      return $this->idNiveau;
    }

    /**
     * setters
     * 
     * @param int $idNiveau
     */
    public function setIdNiveau(int $idNiveau) {
      $this->idNiveau = $idNiveau;
    }

    /**
     * Getters
     * 
     * @return int $idFiliere
     */
    public function getIdFiliere() {
      return $this->idFiliere;
    }

    /**
     * Setters
     * 
     * @param int $idFiliere
     */
    public function setIdFiliere($idFiliere) {
      $this->idFiliere = $idFiliere;
    }
}