<?php

namespace App\Model;

/**
 * Cette classe est un model qui caractérise une Matière
 * 
 * @property int $idMatiere
 * @property int $idProf
 * @property string $nomMatiere
 * @property int $idFiliere
 */
class Matiere {

    /**
     * @var int $idMatiere
     */
    protected $idMatiere;

    /**
     * @var int $idProf
     */
    protected $idProf;

    /**
     * @var string $nomMatiere
     */
    protected $nomMatiere;

    /**
     * @var int $idFiliere
     */
    protected $idFiliere;

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
    * @return int|null
    */
    public function getIdProf():?int {
      return $this->idProf;
    }

    /**
     * Setters
     * 
     * @param int $idProf
     */
    public function setIdProf(int $idProf) {
      $this->idProf = $idProf;
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
}