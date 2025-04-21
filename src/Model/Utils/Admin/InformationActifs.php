<?php

namespace App\Model\Utils\Admin;

class InformationActifs
{
    private int $totalInscrits;
    private int $totalAbsents;
    private int $professeursActifsActuellement;
    private int $professeursAbsentsActuellement;

public function getProfesseursAbsentsActuellement(): int
{
    return $this->professeursAbsentsActuellement;
}

public function setProfesseursAbsentsActuellement(int $value): void
{
    $this->professeursAbsentsActuellement = $value;
}

    public function getTotalInscrits(): int
    {
        return $this->totalInscrits;
    }

    public function setTotalInscrits(int $totalInscrits): void
    {
        $this->totalInscrits = $totalInscrits;
    }

    public function getTotalAbsents(): int
    {
        return $this->totalAbsents;
    }

    public function setTotalAbsents(int $totalAbsents): void
    {
        $this->totalAbsents = $totalAbsents;
    }

    public function getProfesseursActifsActuellement(): int
    {
        return $this->professeursActifsActuellement;
    }

    public function setProfesseursActifsActuellement(int $value): void
    {
        $this->professeursActifsActuellement = $value;
    }
}
