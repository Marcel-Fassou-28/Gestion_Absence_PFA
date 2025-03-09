<?php 

namespace App;

class User {

    /**
     * Propriétés de la classe Utilisateur, mais relatif à un etudiant
     * 
     * @var string $CIN_Etudiant
     */
    private $CIN_Etudiant;

    /** @var string $Nom
     */
    private $Nom;

    /** @var string $prenom
     */
    private $Prenom;

    /** @var string $Password
     */
    private $Password;

    /** @var string $CNE
     */
    private $CNE;

    /** @var string $Email
     */
    private $Email;

    /** @var string $identifiantAdmin
     */
    private $IdentifiantAdmin;

    
    
    /**
     * Constructeur pour initialiser les propriétés
     * 
     * @param array $data Un dictionnaire lui sera passer pour initialiser dynamiquement le constructeur
     * */
    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters
    /**
     * Getter
     * 
     * @return string $CIN_Etudiant
     */
    public function getCIN_Etudiant(): ?string {
        return $this->CIN_Etudiant;
    }

    /**
     * Getter
     * 
     * @return string $Nom
     */
    public function getNom(): ?string {
        return $this->Nom;
    }

    /**
     * Getter
     * 
     * @return string $Prenom
     */
    public function getPrenom(): ?string {
        return $this->Prenom;
    }

    /**
     * Getter
     * 
     * @return string $Password
     */
    public function getPassword(): ?string {
        return $this->Password;
    }

    /**
     * Getter
     * 
     * @return string $CNE
     */
    public function getCNE(): ?string {
        return $this->CNE;
    }

    /**
     * Getter
     * 
     * @return string $Email
     */
    public function getEmail(): ?string {
        return $this->Email;
    }

    /**
     * Getter
     * 
     * @return string $IdentifiantAdmin
     */
    public function getIdentifiantAdmin(): ?string {
        return $this->IdentifiantAdmin;
    }

    // Setters
    /**
     * Setter
     * 
     * @param string $CIN_Etudiant
     */
    public function setCIN_Etudiant($CIN_Etudiant) {
        $this->CIN_Etudiant = $CIN_Etudiant;
    }

    /**
     * Setter
     * 
     * @param string $Nom
     */
    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    /**
     * Setter
     * 
     * @param string $Prenom
     */
    public function setPrenom($Prenom) {
        $this->Prenom = $Prenom;
    }

    /**
     * Setter
     * 
     * @param string $Password
     */
    public function setPassword($Password) {
        $this->Password = $Password; // Optionnel : ajouter un hash ici, ex. password_hash($Password, PASSWORD_DEFAULT)
    }

    /**
     * Setter
     * 
     * @param string $CNE
     */
    public function setCNE($CNE) {
        $this->CNE = $CNE;
    }

    /**
     * Setter
     * 
     * @param string $Email
     */
    public function setEmail($Email) {
        $this->Email = $Email;
    }

    /**
     * Setter
     * 
     * @param string $IdentifiantAdmin
     */
    public function setIdentifiantAdmin($IdentifiantAdmin) {
        $this->IdentifiantAdmin = $IdentifiantAdmin;
    }
}