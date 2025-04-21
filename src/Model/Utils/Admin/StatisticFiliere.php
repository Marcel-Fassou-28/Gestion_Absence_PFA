<?php

namespace App\Model\Utils\Admin;

class StatisticFiliere {
    /**
     * @var string|null
     */
    protected $nomFiliere;

    /**
     * @var int
     */
    protected $totalEtudiants;

    /**
     * @var int
     */
    protected $totalAbsences;

    /**
     * Getters
     *
     * @return string|null
     */
    public function getNomFiliere(): ?string
    {
        return $this->nomFiliere;
    }

    /**
     * @return int
     */
    public function getTotalEtudiants(): int
    {
        return $this->totalEtudiants;
    }

    /**
     * @return int
     */
    public function getTotalAbsences(): int
    {
        return $this->totalAbsences;
    }

    /**
     * Setters
     *
     * @param string|null $nomFiliere
     */
    public function setNomFiliere(?string $nomFiliere): void
    {
        $this->nomFiliere = $nomFiliere;
    }

    /**
     * @param int $totalEtudiants
     */
    public function setTotalEtudiants(int $totalEtudiants): void
    {
        $this->totalEtudiants = $totalEtudiants;
    }

    /**
     * @param int $totalAbsences
     */
    public function setTotalAbsences(int $totalAbsences): void
    {
        $this->totalAbsences = $totalAbsences;
    }
}
