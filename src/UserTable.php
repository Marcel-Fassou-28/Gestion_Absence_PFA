<?php

namespace App;
use App\User;

class UserTable {

    private $table;
    private $pdo;

    public function __construct(\PDO $pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    /**
     * @param string CIN_Etudiant sera utilisé comme paramètre
     * @return object Un object User sera retourner
     */
    public function findByCIN(string $cin_etudiant) {
        $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE cin_etudiant = :cin_etudiant');
        $query->execute(['cin_etudiant' => $cin_etudiant]);
     
        $data = $query->fetch(\PDO::FETCH_ASSOC); // Récupère un tableau associatif
        return $data ? new User($data) : null; //Retourne une classe User ou null
    }
}