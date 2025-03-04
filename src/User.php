<?php 

namespace App;

class User {

    /**
     * Propriétés de la classe Utilisateur, mais relatif à un etudiant
     * 
     * @var string
     */
    private $CIN_Etudiant;
    /** @var string
     */
    private $Nom;
    /** @var string
     */
    private $Prenom;
    /** @var string
     */
    private $Password;
    /** @var string
     */
    private $CNE;
    /** @var string
     */
    private $Email;
    /** @var string
     */
    private $IdentifiantAdmin;

    
    
    /**
     * Constructeur pour initialiser les propriétés
     * @param array Un dictionnaire lui sera passer pour initialiser dynamiquement le constructeur
     * */
    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters
    public function getCIN_Etudiant(): ?string {
        return $this->CIN_Etudiant;
    }

    public function getNom(): ?string {
        return $this->Nom;
    }

    public function getPrenom(): ?string {
        return $this->Prenom;
    }

    public function getPassword(): ?string {
        return $this->Password;
    }

    public function getCNE(): ?string {
        return $this->CNE;
    }

    public function getEmail(): ?string {
        return $this->Email;
    }

    public function getIdentifiantAdmin(): ?string {
        return $this->IdentifiantAdmin;
    }

    // Setters
    public function setCIN_Etudiant($CIN_Etudiant) {
        $this->CIN_Etudiant = $CIN_Etudiant;
    }

    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    public function setPrenom($Prenom) {
        $this->Prenom = $Prenom;
    }

    public function setPassword($Password) {
        $this->Password = $Password; // Optionnel : ajouter un hash ici, ex. password_hash($Password, PASSWORD_DEFAULT)
    }

    public function setCNE($CNE) {
        $this->CNE = $CNE;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function setIdentifiantAdmin($IdentifiantAdmin) {
        $this->IdentifiantAdmin = $IdentifiantAdmin;
    }
}
/*
class User {

    private $cin_etudiant;
    private $nom;
    private $prenom;
    private $password;
    private $cne;
    private $email;
    private $identifiantadmin;
    private $code_filiere;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    *//*public function __construct($cin_etudiant, $nom, $prenom, $password, $cne, $email, $identifiantadmin, $code_filiere)
    {
        $this->cin_etudiant = $cin_etudiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->password = $password;
        $this->cne = $cne;
        $this->email = $email;
        $this->identifiantadmin = $identifiantadmin;
        $this->code_filiere = $code_filiere; 
    }*/

    /*public function setCIN(int $cin): self {
        $this->cin_etudiant = $cin;
        return $this;
    }

    public function setNom(string $nom): self {
        $this->nom = $nom;
        return $this;
    }

    public function setPrenom(string $prenom): self {
        $this->prenom = $prenom;
        return $this;
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function setCNE(int $cne): self {
        $this->cne = $cne;
        return $this;
    }

    public function getNom(): ?string {
        return $this->nom;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function getCNE(): ?string {
        return $this->cne;
    }

    public function getPrenom(): ?string {
        return $this->prenom;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getCIN(): ?string {
        return $this->cin_etudiant;
    }

    public function getCodeFiliere(): ?string {
        return $this->code_filiere;
    }

}*/