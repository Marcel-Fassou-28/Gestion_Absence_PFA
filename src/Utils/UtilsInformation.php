<?php

namespace App\Utils;

use App\Connection;
use App\Abstract\Table;
use App\Model\Creneaux;

/**
 * Cette classe permet d'obtenir les informations nécessaires
 * concernant un professeur, comme :
 *    - La liste de ces créneaux
 *    - Les dernières statistiques
 *    - Les statistiques d'une classe
 */
class UtilsInformation extends Table {
      
    /**
     * Cette méthode retourne la liste des créneaux
     * 
     * @param string $cinProf
     * @return array
     */
    public function getAllCreneaux(string $cinProf):?array {
        $query = $this->pdo->prepare('
            SELECT * FROM creneaux WHERE cinProf = :cinProf
        ');
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $result = $query->fetchAll();

        return $result;
    }

}