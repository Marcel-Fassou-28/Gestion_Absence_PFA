<?php

namespace App;

use App\Abstract\Table;
use App\Model\Etudiant;
use App\Model\Filiere;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\Departement;

class EtudiantTable extends Table {
    
    protected $tableEtudiant = "etudiant";
    protected $tableFiliere = "filiere";
    protected $tableMatiere = "matiere";
    protected $tableClasse = "classe";
    protected $tableDepartement = "departement";

    protected $classEtudiant = Etudiant::class;
    protected $classFiliere = Filiere::class;
    protected $classMatiere = Matiere::class;
    protected $classClasse = Classe::class;
    protected $classDepartement = Departement::class;

    /**
     * Cette méthode permet de retourner la filiere d'un etudiant
     * 
     * @param string $cinEtudiant
     * @return object
     */
    public function getFiliere(string $cinEtudiant) {
        $query = $this->pdo->prepare('
            SELECT f.nomFiliere FROM etudiant e JOIN '.$this->tableClasse.' c ON e.idClasse = c.idClasse
            JOIN '.$this->tableFiliere.' f ON c.idFiliere = f.idFiliere WHERE e.cinEtudiant = :cinEtudiant');

        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $query->fetch();

        return $result;
    }

    /**
     * Cette methode permet d'obtenir le departement d'un utilisateur
     * @param string $cinEtudiant
     * @return object
     */
    public function getDepartementEtudiant(string $cinEtudiant) {
    
        $query = $this->pdo->prepare('
            SELECT DISTINCT d.nomDepartement FROM '.$this->tableEtudiant.' e JOIN '.$this->tableClasse.' c 
            ON e.idClasse = c.idClasse JOIN '.$this->tableFiliere.' f ON c.idFiliere = f.idFiliere 
            JOIN '.$this->tableDepartement.' d ON f.idDepartement = d.idDepartement WHERE e.cinEtudiant = :cinEtudiant');
        
        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, \App\Model\Departement::class);
        $result = $query->fetch();

        return $result;
    }

}