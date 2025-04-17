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
     * Cette methode permet de trouver un utilisateur par son email
     * 
     * @param string $username
     * @return Utilisateur|null
     */
    public function findByEmail(string $email):?Utilisateur {
        $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE email = :email');
        $query->execute(['email' => $email]);
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
     * @param string $cin
     * @return Utilisateur|null
     */
    public function getIdentification(string $cin):?Utilisateur {
        $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE cin = :cin');
        $query->execute(['cin' => $cin]);

        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();

        return $result ?: null;
    }


    public function codeInsertion(string $code, string $email) {
        $query = $this->pdo->prepare('
            UPDATE '. $this->table .' SET codeRecuperation = :code, 
            dateDerniereReinitialisation = NOW() WHERE email = :email
        ');
        $query->execute([
            'code' => $code, 
            'email' => $email
        ]);
    }

    public function codeReset(string $email) {
        $query = $this->pdo->prepare('
            UPDATE '. $this->table .' SET codeRecuperation = " " WHERE email = :email
        ');
        $query->execute([
            'email' => $email
        ]);
    }

    public function changePassword(string $password, string $email) {
        $result = $this->findByEmail($email);

        if ($result !== null && $result->getPassword() != $password ) {
            $query = $this->pdo->prepare('
                    UPDATE '. $this->table .' SET password = :password WHERE email = :email
                ');
            $query->execute([
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'email' => $email
            ]);
            return true;
        }else {
            return false;
        }
        
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
