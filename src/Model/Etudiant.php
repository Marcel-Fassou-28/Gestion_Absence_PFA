<?php

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise un Étudiant
 * 
 * @property int $idEtudiant
 * @property string $cin
 * @property string $nom
 * @property string $prenom
 * @property string $cne
 * @property string $email
 * @property string $nomClasse
 * @property int $idClasse
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
     * @var string $nomClasse
     */
    protected $nomClasse;
    
    /**
     * @var string $cne
     */
    protected $cne;
    
    /**
     * @var string $cinEtudiant
     */
    protected $cinEtudiant;
    
    /**
     * @var string $email
     */
    protected $email;
    
    /**
     * @var int $idClasse
     */
    protected $idClasse;

    /**
     * Constructeur
     */
    /*public function __construct(int $idEtudiant, string $nom, string $prenom, string $cne, string $cinEtudiant, string $email, int $idClasse, string) {
        $this->idEtudiant = $idEtudiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->cne = $cne;
        $this->email = $email;
        $this->nomClasse = $nomClasse;
        $this->cinEtudiant = $cinEtudiant;
        $this->idClasse = $idClasse;
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
        return $this->cinEtudiant; 
    }

    /**
     * Setters
     * 
     * @param string $cinEtudiant
     */
    public function setCIN(string $cinEtudiant) { 
        $this->cinEtudiant = $cinEtudiant; 
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
     * @return int|null
     */
    public function getIdClasse(): ?int { 
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

    public function getNomClasse() {
      return $this->nomClasse;
    }
    public function setNomClasse($value) {
      $this->nomClasse = $value;
    }
}
