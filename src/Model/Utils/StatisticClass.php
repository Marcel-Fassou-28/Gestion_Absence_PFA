<?php

namespace App\Model\Utils;

/**
 * Cette classe est un modèle qui représente les statistiques d'une classe
 * pour un professeur donné, incluant l'effectif total et le nombre d'absents
 *
 * @property int $idClasse
 * @property string $nomClasse
 * @property int $effectifTotal
 * @property int $totalAbsents
 */
class StatisticClass {

    /**
     * @var int $idClasse
     */
    private $idClasse;

    /**
     * @var string $nomClasse
     */
    private $nomClasse;

    /**
     * @var int $effectifTotal
     */
    private $effectifTotal;

    /**
     * @var int $totalAbsents
     */
    private $totalAbsents;

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
     * @return int|null
     */
    public function getEffectifTotal(): ?int {
        return $this->effectifTotal;
    }

    /**
     * Setters
     *
     * @param int $effectifTotal
     */
    public function setEffectifTotal(int $effectifTotal) {
        $this->effectifTotal = $effectifTotal;
    }

    /**
     * Getters
     *
     * @return int|null
     */
    public function getTotalAbsents(): ?int {
        return $this->totalAbsents;
    }

    /**
     * Setters
     *
     * @param int $totalAbsents
     */
    public function setTotalAbsents(int $totalAbsents) {
        $this->totalAbsents = $totalAbsents;
    }
}
