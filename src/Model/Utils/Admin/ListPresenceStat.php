<?php

namespace App\Model\Utils\Admin;

class ListPresenceStat
{
    private int $nombreListesSoumisesAujourdHui;

    public function getNombreListesSoumisesAujourdHui(): int
    {
        return $this->nombreListesSoumisesAujourdHui;
    }

    public function setNombreListesSoumisesAujourdHui(int $value): void
    {
        $this->nombreListesSoumisesAujourdHui = $value;
    }
}
