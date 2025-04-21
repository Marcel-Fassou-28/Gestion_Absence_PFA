<?php

namespace App\Model\Utils\Admin;

class DerniereAbsences
{
    private string $nomClasse;
    private string $creneau;
    private int $nombreAbsents;
    private string $nomMatiere;

    public function getNomMatiere(): string
    {
        return $this->nomMatiere;
    }

    public function getNomClasse(): string
    {
        return $this->nomClasse;
    }

    public function setNomClasse(string $nomClasse): void
    {
        $this->nomClasse = $nomClasse;
    }

    public function getCreneau(): string
    {
        return $this->creneau;
    }

    public function setCreneau(string $creneau): void
    {
        $this->creneau = $creneau;
    }

    public function getNombreAbsents(): int
    {
        return $this->nombreAbsents;
    }

    public function setNombreAbsents(int $nombreAbsents): void
    {
        $this->nombreAbsents = $nombreAbsents;
    }
}
