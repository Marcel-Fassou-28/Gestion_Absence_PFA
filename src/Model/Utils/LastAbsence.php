<?php

namespace App\Model\Utils;

/**
 * Cette classe est un modèle qui caractérise une absence récente
 * 
 * @property string $nomClasse
 * @property string $nomFiliere
 * @property string $nomMatiere
 * @property string $heureDebut
 * @property string $heureFin
 * @property string $date
 * @property int $nombreAbsents
 */
class LastAbsence {
    /**
     * @var string $nomClasse
     */
    private $nomClasse;

    /**
     * @var string $nomFiliere
     */
    private $nomFiliere;

    /**
     * @var string $nomMatiere
     */
    private $nomMatiere;

    /**
     * @var string $heureDebut
     */
    private $heureDebut;

    /**
     * @var string $heureFin
     */
    private $heureFin;

    /**
     * @var string $date
     */
    private $date;

    /**
     * @var int $nombreAbsents
     */
    private $nombreAbsents;

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomClasse(): ?string {
        return $this->nomClasse;
    }

    /**
     * Setters
     * 
     * @param string $nomClasse
     */
    public function setNomClasse(string $nomClasse) {
        $this->nomClasse = $nomClasse;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomFiliere(): ?string {
        return $this->nomFiliere;
    }

    /**
     * Setters
     * 
     * @param string $nomFiliere
     */
    public function setNomFiliere(string $nomFiliere) {
        $this->nomFiliere = $nomFiliere;
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
    public function setNomMatiere(string $nomMatiere) {
        $this->nomMatiere = $nomMatiere;
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
    public function setHeureDebut(string $heureDebut) {
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
    public function setHeureFin(string $heureFin) {
        $this->heureFin = $heureFin;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getDate(): ?string {
        return $this->date;
    }

    /**
     * Setters
     * 
     * @param string $date
     */
    public function setDate(string $date) {
        $this->date = $date;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getNombreAbsents(): ?int {
        return $this->nombreAbsents;
    }

    /**
     * Setters
     * 
     * @param int $nombreAbsents
     */
    public function setNombreAbsents(int $nombreAbsents) {
        $this->nombreAbsents = $nombreAbsents;
    }
}
