<?php

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise un Étudiant
 * 
 * @property int $idEtudiant
 * @property string $nom
 * @property string $prenom
 * @property string $cne
 * @property string $cin
 * @property string $email
 * @property string $password
 * @property int $idFiliere
 */
class Etudiant {
    /**
     * @var int $idEtudiant
     */
    protected $idEtudiant;
    
    /**
     * @var string $nom
     */
    protected $nom;
    
    /**
     * @var string $prenom
     */
    protected $prenom;
    
    /**
     * @var string $cne
     */
    protected $cne;
    
    /**
     * @var string $cin
     */
    protected $cin;
    
    /**
     * @var string $email
     */
    protected $email;
    
    /**
     * @var string $password
     */
    protected $password;
    
    /**
     * @var int $idFiliere
     */
    protected $idFiliere;

    /**
     * Constructeur
     */
    /*public function __construct(int $idEtudiant, string $nom, string $prenom, string $cne, string $cin, string $email, string $password, int $idFiliere) {
        $this->idEtudiant = $idEtudiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->cne = $cne;
        $this->cin = $cin;
        $this->email = $email;
        $this->password = $password;
        $this->idFiliere = $idFiliere;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdEtudiant(): ?int { 
        return $this->idEtudiant; 
    }

    /**
     * Setters
     * 
     * @param int $idEtudiant
     */
    public function setIdEtudiant(int $idEtudiant) { 
        $this->idEtudiant = $idEtudiant; 
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNom(): ?string { 
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
    public function getPrenom(): ?string { 
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
    public function getCNE(): ?string { 
        return $this->cne; 
    }

    /**
     * Setters
     * 
     * @param string $cne
     */
    public function setCNE(string $cne) { 
        $this->cne = $cne; 
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getCIN(): ?string { 
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

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getEmail(): ?string { 
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
    public function getPassword(): ?string {
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
     * @return int|null
     */
    public function getIdFiliere(): ?int { 
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
