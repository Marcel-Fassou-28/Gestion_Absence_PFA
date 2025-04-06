<?php
namespace App;


use App\Model\Departement;;  
use App\ProfessorTable;

class listProfTable extends ProfessorTable {
    protected $tableDepartement = "departement";
    protected $classDepartement = Departement::class;



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

}
?>