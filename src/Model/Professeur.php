<?php


namespace App\Model;


/**
 * Cette classe est un model qui caractérise un Professeur
 * 
 * @property int $idProf
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property string $cin
 */
class Professeur {

    /**
     * @var int $idProf
     */
    private $idProf;

    /**
     * @var string $nom
     */
    private $nom;

    /**
     * @var string $prenom
     */
    private $prenom;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $cin
     */
    private $cinProf;

    /*public function __construct($idProf, $nom, $prenom, $email, $password, $cinProf)
    {
        $this->idProf = $idProf;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->cinProf = $cinProf;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDProf():?int {
      return $this->idProf;
    }

    /**
     * Setters
     * 
     * @param int $idProf
     */
    public function setIDProf(string $idProf) {
      $this->idProf = $idProf;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNom():?string {
      return $this->nom;
    }

    /**
     * Setters
     * 
     * @param string $nom
     */
    public function setNom(string $nom) {
      $this->nom = $nom;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getPrenom():?string {
      return $this->prenom;
    }

    /**
     * Setters
     * 
     * @param string $prenom
     */
    public function setPrenom(string $prenom) {
      $this->prenom = $prenom;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getEmail() :?string {
      return $this->email;
    }

    /**
     * Setters
     * 
     * @param string $email
     */
    public function setEmail(string $email) {
      $this->email = $email;
    }

     /**
    * Getters
    * 
    * @return string|null
    */
    public function getCIN():?string {
      return $this->cinProf;
    }

    /**
     * Setters
     * 
     * @param string $cin
     */
    public function setCIN(string $cinProf) {
      $this->cinProf = $cinProf;
    }
}