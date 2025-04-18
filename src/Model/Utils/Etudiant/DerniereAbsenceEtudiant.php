<?php

namespace App\Model\Utils\Etudiant;

/**
 * Cette classe représente le profil d’un étudiant et sa dernière absence
 * 
 * @property string $nom
 * @property string $prenom
 * @property string $nomClasse
 * @property string $nomFiliere
 * @property string $nomDepartement
 * @property string $nomMatiere
 * @property string $dateDerniereAbsence
 */
class DerniereAbsenceEtudiant {

    private $nom;
    private $prenom;
    private $nomClasse;
    private $nomFiliere;
    private $nomDepartement;
    private $nomMatiere;
    private $dateDerniereAbsence;

    public function getNom(): ?string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): ?string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getNomClasse(): ?string {
        return $this->nomClasse;
    }

    public function setNomClasse(string $nomClasse): void {
        $this->nomClasse = $nomClasse;
    }

    public function getNomFiliere(): ?string {
        return $this->nomFiliere;
    }

    public function setNomFiliere(string $nomFiliere): void {
        $this->nomFiliere = $nomFiliere;
    }

    public function getNomDepartement(): ?string {
        return $this->nomDepartement;
    }

    public function setNomDepartement(string $nomDepartement): void {
        $this->nomDepartement = $nomDepartement;
    }

    public function getNomMatiere(): ?string {
        return $this->nomMatiere;
    }

    public function setNomMatiere(string $nomMatiere): void {
        $this->nomMatiere = $nomMatiere;
    }

    public function getDateDerniereAbsence(): ?string {
        return $this->dateDerniereAbsence;
    }

    public function setDateDerniereAbsence(string $date): void {
        $this->dateDerniereAbsence = $date;
    }
}
