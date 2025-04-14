<?php

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise un Absents
 * 
 * @property string $cinEtudiant
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property string $cne
 * @property int $nbrAbsence
 */
class Absents {
    /**
     * @var string $cinEtudiant
     */
    private $cinEtudiant;
    
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
     * @var string $cne
     */
    private $cne;
    
    /**
     * @var int $nbrAbsence
     */
    private $nbrAbsence;

    /**
     * Constructeur
     */
    /*public function __construct(string $cinEtudiant, string $nom, string $prenom,string $email, string $cne, int $nbrAbsence) {
        $this->cinEtudiant = $cinEtudiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->cne = $cne;
        $this->nbrAbsence = $nbrAbsence;
    }*/

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
     * Setters
     * 
     * @param string $prenom
     */
    public function setEmail(string $email) { 
        $this->email = $email; 
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
    public function getCINEtudiant(): ?string { 
        return $this->cinEtudiant; 
    }

    /**
     * Setters
     * 
     * @param string $cinEtudiant
     */
    public function setCINEtudiant(string $cinEtudiant) { 
        $this->cinEtudiant = $cinEtudiant; 
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getNbrAbsence(): ?int { 
        return $this->nbrAbsence; 
    }

    /**
     * Setters
     * 
     * @param int $nbrAbsence
     */
    public function setNbrAbsence(int $nbrAbsence) {
        $this->nbrAbsence = $nbrAbsence; 
    }
}
