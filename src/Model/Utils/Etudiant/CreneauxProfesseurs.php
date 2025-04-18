<?php

namespace App\Model\Utils\Etudiant;

/**
 * Cette classe représente un créneau associé à un étudiant,
 * incluant le jour, l'heure et le professeur de la matière.
 * 
 * @property string $jourSemaine
 * @property string $heureDebut
 * @property string $heureFin
 * @property string $nomMatiere
 * @property string $nomProfesseur
 */
class CreneauxProfesseurs {

    /**
     * @var string $jourSemaine
     */
    private $jourSemaine;

    /**
     * @var string $heureDebut
     */
    private $heureDebut;

    /**
     * @var string $heureFin
     */
    private $heureFin;

    /**
     * @var string $nomMatiere
     */
    private $nomMatiere;

    /**
     * @var string $nomProfesseur
     */
    private $nomProfesseur;

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getJourSemaine(): ?string {
        return $this->jourSemaine;
    }

    /**
     * Setters
     * 
     * @param string $jourSemaine
     */
    public function setJourSemaine(string $jourSemaine): void {
        $this->jourSemaine = $jourSemaine;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getHeureDebut(): ?string {
        return $this->heureDebut;
    }

    /**
     * Setters
     * 
     * @param string $heureDebut
     */
    public function setHeureDebut(string $heureDebut): void {
        $this->heureDebut = $heureDebut;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getHeureFin(): ?string {
        return $this->heureFin;
    }

    /**
     * Setters
     * 
     * @param string $heureFin
     */
    public function setHeureFin(string $heureFin): void {
        $this->heureFin = $heureFin;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomMatiere(): ?string {
        return $this->nomMatiere;
    }

    /**
     * Setters
     * 
     * @param string $nomMatiere
     */
    public function setNomMatiere(string $nomMatiere): void {
        $this->nomMatiere = $nomMatiere;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomProfesseur(): ?string {
        return $this->nomProfesseur;
    }

    /**
     * Setters
     * 
     * @param string $nomProfesseur
     */
    public function setNomProfesseur(string $nomProfesseur): void {
        $this->nomProfesseur = $nomProfesseur;
    }
}
