<?php

namespace App\Model\Utils;

class CreneauComplet
{
    /**
     * @var string
     */
    private $jour;

    /**
     * @var string
     */
    private $heureDebut;

    /**
     * @var string
     */
    private $heureFin;

    /**
     * @var string|null
     */
    private $nomMatiere;

    /**
     * @var string|null
     */
    private $nomClasse;

    /**
     * Getters
     *
     * @return string|null
     */
    public function getJour(): ?string
    {
        return $this->jour;
    }

    /**
     * Getters
     *
     * @return string|null
     */
    public function getHeureDebut(): ?string
    {
        return $this->heureDebut;
    }

    /**
     * Getters
     *
     * @return string|null
     */
    public function getHeureFin(): ?string
    {
        return $this->heureFin;
    }

    /**
     * Getters
     *
     * @return string|null
     */
    public function getNomMatiere(): ?string
    {
        return $this->nomMatiere;
    }

    /**
     * Getters
     *
     * @return string|null
     */
    public function getNomClasse(): ?string
    {
        return $this->nomClasse;
    }

    /**
     * Setters
     *
     * @param string $jour
     */
    public function setJour(string $jour): void
    {
        $this->jour = $jour;
    }

    /**
     * Setters
     *
     * @param string $heureDebut
     */
    public function setHeureDebut(string $heureDebut): void
    {
        $this->heureDebut = $heureDebut;
    }

    /**
     * Setters
     *
     * @param string $heureFin
     */
    public function setHeureFin(string $heureFin): void
    {
        $this->heureFin = $heureFin;
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
     * @param string|null $nomClasse
     */
    public function setNomClasse(?string $nomClasse): void
    {
        $this->nomClasse = $nomClasse;
    }
}