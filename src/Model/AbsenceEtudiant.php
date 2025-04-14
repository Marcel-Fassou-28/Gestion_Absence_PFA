<?php

namespace App\Model;

class AbsenceEtudiant {
    private $cinEtudiant;
    private $nom;
    private $prenom;
    private $email;
    private $cne;
    private $nbrAbsence;

    public function __construct($cinEtudiant, $nom, $prenom, string $email, $cne, $nbrAbsence) {
        $this->cinEtudiant = $cinEtudiant;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->cne = $cne;
        $this->nbrAbsence = $nbrAbsence;
    }

    public function getCINEtudiant() {
        return $this->cinEtudiant;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getPrenom() {
        return $this->prenom;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getCNE() {
        return $this->cne;
    }
    public function getNbrAbsence() {
        return $this->nbrAbsence;
    }
}
