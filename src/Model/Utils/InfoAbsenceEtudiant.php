<?php

namespace App\Model\Utils;

class InfoAbsenceEtudiant {

    /**
     * @var string
     */
    private $cinEtudiant;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $cne;

    /**
     * @var string|null
     */
    private $nomClasse;

    /**
     * @var string|null
     */
    private $nomMatiere;

    /**
     * @var string|null
     */
    private $dateAbsence;

    /**
     * @var string|null
     */
    private $heureDebut;

    /**
     * @var string|null
     */
    private $heureFin;

    /**
     * @var int
     */
    private $nombreAbsences;

    /**
     * Getters
     *
     * @return string|null
     */
    public function getCinEtudiant(): ?string {
        return $this->cinEtudiant;
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
     * Getters
     *
     * @return string|null
     */
    public function getPrenom(): ?string {
        return $this->prenom;
    }

    /**
     * Getters
     *
     * @return string|null
     */
    public function getCne(): ?string {
        return $this->cne;
    }

    /**
     * Getters
     *
     * @return string|null
     */
    public function getNomClasse(): ?string {
        return $this->nomClasse;
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
     * Getters
     *
     * @return string|null
     */
    public function getDateAbsence(): ?string {
        return $this->dateAbsence;
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
     * Getters
     *
     * @return string|null
     */
    public function getHeureFin(): ?string {
        return $this->heureFin;
    }

    /**
     * Getters
     *
     * @return int
     */
    public function getNombreAbsences(): int {
        return $this->nombreAbsences;
    }

    /**
     * Setters
     *
     * @param string $cinEtudiant
     */
    public function setCinEtudiant(string $cinEtudiant): void {
        $this->cinEtudiant = $cinEtudiant;
    }

    /**
     * Setters
     *
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * Setters
     *
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * Setters
     *
     * @param string $cne
     */
    public function setCne(string $cne): void
    {
        $this->cne = $cne;
    }

    /**
     * Setters
     *
     * @param string|null $nomClasse
     */
    public function setNomClasse(?string $nomClasse): void
    {
        $this->nomClasse = $nomClasse;
    }

    /**
     * Setters
     *
     * @param string|null $nomMatiere
     */
    public function setNomMatiere(?string $nomMatiere): void
    {
        $this->nomMatiere = $nomMatiere;
    }

    /**
     * Setters
     *
     * @param string|null $dateAbsence
     */
    public function setDateAbsence(?string $dateAbsence): void
    {
        $this->dateAbsence = $dateAbsence;
    }

    /**
     * Setters
     *
     * @param string|null $heureDebut
     */
    public function setHeureDebut(?string $heureDebut): void
    {
        $this->heureDebut = $heureDebut;
    }

    /**
     * Setters
     *
     * @param string|null $heureFin
     */
    public function setHeureFin(?string $heureFin): void
    {
        $this->heureFin = $heureFin;
    }

    /**
     * Setters
     *
     * @param int $nombreAbsences
     */
    public function setNombreAbsences(int $nombreAbsences): void
    {
        $this->nombreAbsences = $nombreAbsences;
    } 
}
