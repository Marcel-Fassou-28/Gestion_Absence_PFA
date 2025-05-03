<?php

namespace App\Abstract;

abstract class Table {
    protected $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Cette methode permet d'obtenir le departement d'un professeur
     * @param string $cin
     * @return object
     */
    public function getDepartementProf(string $cin) {
    
        $query = $this->pdo->prepare('
            SELECT DISTINCT d.nomDepartement FROM utilisateur u JOIN professeur p ON u.cin = p.cinProf
            JOIN matiere m ON m.cinProf = p.cinProf JOIN filiere f ON m.idFiliere = f.idFiliere
            JOIN departement d ON f.idDepartement = d.idDepartement WHERE u.cin = :cin');
        
        $query->execute(['cin' => $cin]);
        $query->setFetchMode(\PDO::FETCH_CLASS, \App\Model\Departement::class);
        $result = $query->fetch();

        if (!$result) {
            return null;
        }
        return $result ;
    }

}