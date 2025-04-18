<?php

namespace App\Model\Utils\Etudiant;

/**
 * Cette classe est un modèle qui représente le nombre d'absences d’un étudiant par matière
 * 
 * @property string $nomMatiere
 * @property int $nombreAbsences
 */
class AbsenceParMatiere {
    /**
     * @var string $nomMatiere
     */
    private $nomMatiere;

    /**
     * @var int $nombreAbsences
     */
    private $nombreAbsences;

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
     * @return int|null
     */
    public function getNombreAbsences(): ?int {
        return $this->nombreAbsences;
    }

    /**
     * Setters
     * 
     * @param int $nombreAbsences
     */
    public function setNombreAbsences(int $nombreAbsences): void {
        $this->nombreAbsences = $nombreAbsences;
    }
}
