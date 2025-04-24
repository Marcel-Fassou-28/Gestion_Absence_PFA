<?php

namespace App\Model\Utils\Admin;

/**
 * Cette classe est un model qui caractérise une Matière
 * 
 * @property int $idMatiere
 * @property string $cinProf
 * @property string $nomMatiere
 * @property int $idFiliere
 * @property int $idClasse
 * @property string $nomProf
 * @property string $prenomProf
 * @property string $nomClasse
 * 
 */
class MatiereProf {

    /**
     * @var int $idMatiere
     */
    protected $idMatiere;

    /**
     * @var string $cinProf
     */
    protected $cinProf;

    /**
     * @var string $nomMatiere
     */
    protected $nomMatiere;

    /**
     * @var string $nomProf
     */
    protected $nomProf;

    /**
     * @var string $prenomProf
     */
    protected String $prenomProf;

    /**
     * @var int $idFiliere
     */
    protected $idFiliere;

    /**
     * @var int $idClasse
     */
    protected $idClasse;

    /**
     * @var string $nomClasse
     */
    protected string $nomClasse;

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdMatiere():?int {
      return $this->idMatiere;
    }

    /**
    * Setters
    * 
    * @param int $idMatiere
    */
    public function setIdMatiere(int $idMatiere) {
      $this->idMatiere = $idMatiere;
    }

    /**
    * Getters
    * 
    * @return string|null
    */
    public function getCINProf():?string {
      return $this->cinProf;
    }

    /**
     * Setters
     * 
     * @param string $cinProf
     */
    public function setIdProf(string $cinProf) {
      $this->cinProf = $cinProf;
    }

    /**
    * Getters
    * 
    * @return string|null
    */
    public function getNomMatiere():?string {
      return $this->nomMatiere;
    }

    /**
    * Setters
    * 
    * @param string $nomMatiere
    */
    public function setNomMatiere(string $nomMatiere) {
      $this->nomMatiere = $nomMatiere;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdFiliere():?int {
      return $this->idFiliere;
    }

    /**
     * Setters
     * 
     * @param int $idFiliere
     */
    public function setIdFiliere(int $idFiliere) {
      $this->idFiliere = $idFiliere;
    }

    /** 
    * @return int|null
    */
   public function getIdClasse():?int {
     return $this->idClasse;
   }

   /**
    * Setters
    * 
    * @param int $idClasse
    */
   public function setIdClasse(int $idClasse) {
     $this->idClasse = $idClasse;
   }

    public function getNomProf() {
      return $this->nomProf;
    }
    public function setNomProf($value) {
      $this->nomProf = $value;
    }

    public function getNomClasse() {
        return $this->nomClasse;
      }
      public function setNomclasse($value) {
        $this->nomClasse = $value;
      }

    public function getPrenomProf() {
      return $this->prenomProf;
    }
    public function setPrenomProf($value) {
      $this->prenomProf = $value;
    }
}