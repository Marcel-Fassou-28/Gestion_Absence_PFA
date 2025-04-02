<?php


namespace App\Model;


/**
 * Cette classe est un model qui caractérise un Professeur
 * 
 * @property int $idProf
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property string $password
 * @property string $cin
 */
class Professeur {

    /**
     * @var int $idProf
     */
    protected $idAdmin;

    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var string $prenom
     */
    protected $prenom;

    /**
     * @var string $email
     */
    protected $email;

    /**
     * @var string $password
     */
    protected $password;

    /**
     * @var string $cin
     */
    protected $cin;

    /*public function __construct($idProf, $nom, $prenom, $email, $password, $cin)
    {
        $this->idProf = $idProf;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->cin = $cin;
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
    public function getPassword():?string {
      return $this->password;
    }

    /**
     * Setters
     * 
     * @param string $password
     */
    public function setPassword(string $password) {
      $this->password = $password;
    }

    
     /**
    * Getters
    * 
    * @return string|null
    */
    public function getCIN():?string {
      return $this->cin;
    }

    /**
     * Setters
     * 
     * @param string $cin
     */
    public function setCIN(string $cin) {
      $this->cin = $cin;
    }
}