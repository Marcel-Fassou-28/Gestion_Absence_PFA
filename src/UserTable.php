<?php

namespace App;
use App\Model\Utilisateur;
use App\Abstract\Table;

class UserTable extends Table {
    
    protected $table = "utilisateur";
    protected $class = Utilisateur::class;

    /**
     * @var bool|null $errorMessage
     */
    protected $errorMessage;

    /**
     * Cette methode permet de trouver un nom d'utilisateur 
     * 
     * @param string $username
     * @return Utilisateur|null
     */
    public function findByUsername(string $username):?Utilisateur {
        $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE username = :username');
        $query->execute(['username' => $username]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();

        if ($result === false) {
            $this->errorMessage = true;
        }
        return $result ?: null;
    }


    /**
     * Cette methode a pour role de reourner les information d'un utilisateur
     * 
     * @param string $role
     * @return object
     */
    public function getIdentification(string $cin):Utilisateur {
        $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE cin = :cin');
        $query->execute(['cin' => $cin]);

        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();

        return $result;
    }

    /**
     * Getters
     * 
     * @return bool
     */
    public function getErrorMessage() :bool {
        return $this->errorMessage;
    }
}
