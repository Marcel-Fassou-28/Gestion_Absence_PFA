<?php

namespace App;
use App\User;
use Exception;

class UserTable {

    private $table;
    private $class = User::class;
    private $pdo;

    public function __construct(\PDO $pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function findByCIN(string $cin_etudiant) {
        $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE cin_etudiant = :cin_etudiant');
        $query->execute(['cin_etudiant' => $cin_etudiant]);
        /*$query->setFetchMode(\PDO::FETCH_CLASS, $this->class);

        $result = $query->fetch();
        if ($result === false) {
            throw new Exception("Non trouvé");
        }
        return $result;*/
        $data = $query->fetch(\PDO::FETCH_ASSOC); // Récupère un tableau associatif
        return $data ? new User($data) : null;
    }
}