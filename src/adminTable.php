<?php
namespace App;


use App\Model\Departement;
use App\Model\Filiere;
use App\Model\Classe;
use App\Model\Etudiant;
use App\Model\Professeur;
use App\Model\Absence;
use App\Model\Matiere;
use App\Model\Utilisateur;

use App\Model\Justificatif;  
use App\Professeur\ProfessorTable;

class adminTable extends ProfessorTable {
    protected $tableDepartement = "departement";
    protected $tableFiliere = "filiere";
    protected $tableClasse = "classe";
    protected $tableProf = "professeur";
    protected $tableEtudiant = "etudiant";
    protected $tableMatiere = "matiere";
    protected $tableAbsence = "absence";
    
    protected $tableJustificatif = "justificatif";

    protected $classDepartement = Departement::class;
    protected $classJustificatif = Justificatif::class;
    protected $classFiliere = Filiere::class;
    protected $classClasse = Classe::class;
    protected $classProf = Professeur::class;
    protected $classEtudiant = Etudiant::class;
    protected $classAbsence = Absence::class;
    protected $classMatiere = Matiere::class;
    


/**
 * cette fonction d'avoir la liste de tous les elements
 * @param string $table
 * @return array
 */
public function getAll( string $table,$class):array {
    $query = $this->pdo->prepare("SELECT * FROM $table");
    $query->execute();
    $query->setFetchMode(\PDO::FETCH_CLASS, $this->$class);
    $result = $query->fetchAll();
    return count($result) != 0 ? $result : [];
}
public function fieldsByDepartement(string $departement):array{
    $query = $this->pdo->prepare('
        SELECT DISTINCT f.nomFiliere FROM '. 
        $this->tableFiliere . ' f JOIN ' . $this->tableDepartement . ' d 
        ON d.idDepartement = f.idDepartement WHERE d.nomDepartement = :departement');
    $query->execute(['departement' => $departement]);
    $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
    $result = $query->fetchAll();
    return count($result) != 0 ? $result : [];
}
public function classByFields( string $filiere): array{
    $query = $this->pdo->prepare('
        SELECT DISTINCT c.nomClasse FROM ' 
        . $this->tableClasse . ' c JOIN '
        . $this->tableFiliere . ' f ON 
        f.idFiliere = c.idFiliere  WHERE f.nomFiliere = :filiere');
    $query->execute(['filiere' => $filiere]);
    $query->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
    $result = $query->fetchAll();
    return count($result) != 0 ? $result : [];
}
public function getprofByDepartement(string $depart):array{
    $query = $this->pdo->prepare('
    SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM '.
    $this->tableMatiere . ' m JOIN ' . $this->tableProf .'
    p ON m.cinProf = p.cinProf JOIN '. $this->tableFiliere. '
    f ON m.idFiliere = f.idFiliere JOIN '. $this->tableDepartement .'
    d ON f.idDepartement = d.idDepartement WHERE  
    d.nomDepartement = :departement');
    $query->execute(['departement' => $depart]);
    $query->setFetchMode(\PDO::FETCH_CLASS,$this->classProf);
    $result=$query->fetchAll();
    return count($result) != 0 ? $result : [];
}

public function getProfByFiliere(string $filiere):array{
    $query = $this->pdo->prepare('
        SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM '.
        $this->tableMatiere . ' m JOIN ' . $this->tableProf .'
        p ON m.cinProf = p.cinProf JOIN '. $this->tableFiliere. '
        f ON m.idFiliere = f.idFiliere WHERE  
        f.nomFiliere = :filiere');
    $query->execute(['filiere' => $filiere]);
    $query->setFetchMode(\PDO::FETCH_CLASS,$this->classProf);
    $result=$query->fetchAll();
    return count($result) != 0 ? $result : [];
}

public function getProfByClass(string $class):array{
    $query = $this->pdo->prepare('
        SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM '.
        $this->tableMatiere . ' m JOIN ' . $this->tableProf .'
        p ON m.cinProf = p.cinProf JOIN '. $this->tableClasse. '
        c ON m.idClasse = c.idClasse WHERE  
        c.nomClasse = :class');
    $query->execute(['class' => $class]);
    $query->setFetchMode(\PDO::FETCH_CLASS,$this->classProf);
    $result=$query->fetchAll();
    return count($result) != 0 ? $result : [];
}

public function getAllJustificatif():array{
    $query = $this->pdo->prepare('
    SELECT e.nom as nom,e.prenom as prenom, j.dateSoumission as 
    Date_Soumission FROM '.$this->tableAbsence .' a JOIN '
    . $this->tableJustificatif . ' j ON a.idAbsence = j.idAbsence'
    . ' JOIN ' . $this->tableEtudiant . ' e ON e.cinEtudiant = a.cinEtudiant');
    $query->execute();
    $result = $query->fetchALL();
    return count($result) != 0 ? $result : [];
}

}
?>