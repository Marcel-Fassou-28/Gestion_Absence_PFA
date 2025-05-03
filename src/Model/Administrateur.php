<?php

namespace App\Model;


/**
 * Cette classe est un model qui caractérise un administrateur
 * 
 * @property int $idAdmin
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property string $cin
 */
class Administrateur {

    /**
     * @var int $idAdmin
     */
    private $idAdmin;

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
     * @var string $cinAdmin
     */
    private $cinAdmin;

    /*public function __construct($idAdmin, $nom, $prenom, $email, $cinAdmin)
    {
        $this->idAdmin = $idAdmin;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->cinAdmin = $cinAdmin;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIDAdmin():?int {
      return $this->idAdmin;
    }

    /**
     * Setters
     * 
     * @param int $idAdmin
     */
    public function setIDAdmin(string $idAdmin) {
      $this->idAdmin = $idAdmin;
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
      return $this->cinAdmin;
    }

    /**
     * Setters
     * 
     * @param string $cinAdmin
     */
    public function setCIN(string $cinAdmin) {
      $this->cinAdmin = $cinAdmin;
    }
}