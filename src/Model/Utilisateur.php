<?php

namespace App\Model;


/**
 * Cette classe est un model qui caractérise un utilisateur
 * 
 * @property int $id
 * @property string $username
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property string $password
 * @property string $cin
 * @property string $role
 * @property $photo
 */
class Utilisateur {

    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $username
     */
    public $username;

    /**
     * @var string $nom
     */
    public $nom;

    /**
     * @var string $prenom
     */
    public $prenom;

    /**
     * @var string $email
     */
    public $email;

    /**
     * @var string $password
     */
    public $password;

    /**
     * @var string $cin
     */
    public $cin;

    /**
     * @var string $role
     */
    public $role;

    /**
     * @var $photo
     */
    public $photo;

    /*public function __construct($id, $username, $nom, $prenom, $email, $password, $cin, $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->cin = $cin;
        $this->role = $role;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getID() :?int {
      return $this->id;
    }

    /**
     * Setters
     *  
     * @param int $id
     */
    public function setIDAdmin(int $id) {
      $this->id = $id;
    }

    /** 
     * Getters
     * 
     * @return string|null
     */
    public function getUsername() :?string {
        return $this->username;
      }
  
    /**
    * Setters
    * 
    * @param string $username
    */
      public function setUsername(string $username) {
        $this->username = $username;
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
    public function getEmail():?string {
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
    public function getRole():?string {
        return $this->role;
    }
  
    /**
    * Setters
    * 
    * @param string $role
    */
    public function setRole(string $role) {
        $this->role = $role;
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