<?php

namespace App\Model\Utils\Admin;

/**
 * Cette classe est définie pour caractériser un Niveau
 * 
 * @property int $idClasse
 * @property string $nomClasse
 * @property int $idNiveau
 * @property int $idFiliere
 * @property string $nomNiveau
 * @property string $nomFiliere
 */
class ClasseFiliere {

    /**
     * @var int $idClasse
     */
    protected $idClasse;

    /**
     * @var string $nomClasse
     */
    protected $nomClasse;

    /**
     * @var string $nomFiliere
     */
    protected $nomFiliere;

    /**
     * @var string $nomNiveau
     */
    protected $nomNiveau;

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

    public function getNomFiliere() {
      return $this->nomFiliere;
    }
    public function setNomFiliere($value) {
      $this->nomFiliere = $value;
    }

    public function getNomNiveau() {
      return $this->nomNiveau;
    }
    public function setNomNiveau($value) {
      $this->nomNiveau = $value;
    }
}