<?php

namespace App\Model;

/**
 * Cette classe est définie pour caractériser un Créneau
 * @property int $id
 * @property string $jourSemaine
 * @property string $heureDebut
 * @property string $heureFin
 * @property string $cinProf
 * @property int $idMatiere
 * @property string $nomMatiere
 * @property string $nomProf
 * @property string $prenomProf
 */
class Creneaux {

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $jourSemaine
     */
    private $jourSemaine;

    /**
     * @var string $heureDebut
     */
    private $heureDebut;

    /**
     * @var string $heureFin
     */
    private $heureFin;

    /**
     * @var string $cinProf
     */
    private $cinProf;

    /**
     * @var string $nomProf
     */
    private $nomProf;

        /**
     * @var string $prenomProf
     */
    private $prenomProf;

    /**
     * @var string $nomMatiere
     */
    private $nomMatiere;

    /**
     * @var string $nomClasse
     */
    private $nomClasse;

    /**
     * @var int $idMatiere
     */
    private $idMatiere;

    /*public function __construct(int $id, string $jourSemaine,string $heureDebut,string $heureFin,string $cinProf,int $idMatiere, string $nomMatiere, string $nomProf, string $nomClasse, string $prenomProf)
    {
        $this->id = $id;
        $this->jourSemaine = $jourSemaine;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->cinProf = $cinProf;
        $this->nomProf = $nomProf;
        $this->prenomProf = $prenomProf;
        $this->nomMatiere = $nomMatiere;
        $this->nomClasse = $nomClasse;
        $this->idMatiere = $idMatiere;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getID() : ?int {
        return $this->id;
    }

    /**
     * Setters
     * 
     * @param int|null
     */
    public function setID(int $id) {
        $this->id = $id;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getJourSemaine() : ?string {
        return $this->jourSemaine;
    }

    /**
     * Setters
     * 
     * @param string|null
     */
    public function setJourSemaine(string $jourSemaine) {
        $this->jourSemaine = $jourSemaine;
    }


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

    public function getNomProf() {
      return $this->nomProf;
    }
    public function setNomProf($value) {
      $this->nomProf = $value;
    }

    public function getPrenomProf() {
        return $this->prenomProf;
      }
      public function setPrenomProf($value) {
        $this->prenomProf = $value;
      }

    public function getNomMatiere() {
      return $this->nomMatiere;
    }
    public function setNomMatiere($value) {
      $this->nomMatiere = $value;
    }

    public function getNomClasse() {
      return $this->nomClasse;
    }
    public function setNomClasse($value) {
      $this->nomClasse = $value;
    }
}
