<?php

namespace App;

use App\Model\Etudiant;
use App\Model\Filiere;
use App\Abstract\Table;
use App\Model\Absence;
use App\Model\Absents;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\Niveau;
use App\Model\Professeur;
use DateTime;

class ProfessorTable extends Table {
    
    protected $tableEtudiant = "etudiant";
    protected $tableFiliere = "filiere";
    protected $tableMatiere = "matiere";
    protected $tableProf = "professeur";
    protected $tableClasse = "classe";
    protected $tableNiveau = "niveau";
    protected $tableAbsence = "absence";

    protected $classEtudiant = Etudiant::class;
    protected $classFiliere = Filiere::class;
    protected $classMatiere = Matiere::class;
    protected $classProf = Professeur::class;
    protected $classClasse = Classe::class;
    protected $classAbsence = Absence::class;
    protected $classAbsents = Absents::class;

    /**
     * @var bool|null $errorMessage
     */
    protected $errorMessage;


    /**
     * Cette fonction permet d'obtenir les noms des etudiants par classe 
     * 
     * @param string $cinProf
     * 
     * @return array
     */
    public function findStudent(string $cinProf):array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email
            FROM '. $this->tableEtudiant.' e JOIN '.$this->tableClasse.' c ON e.idClasse = c.idClasse JOIN 
            '.$this->tableMatiere.' m ON c.idClasse = m.idMatiere WHERE m.cinProf = :cinProf');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette fonction permet d'obtenir les noms des etudiants par classe 
     * 
     * @param string $class
     * 
     * @return array
     */
    public function findStudentByClass(string $class):array {
        $query = $this->pdo->prepare('
            SELECT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email FROM 
            '. $this->tableEtudiant .' e JOIN '. $this->tableClasse . '
             c ON e.idClasse = c.idClasse WHERE c.nomClasse = :class');

        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette fonction permet de retourner la liste des filiere
     * @param string $cinProf
     * 
     * @return array|null
     */
    public function getFiliere(string $cinProf) :?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT f.idFiliere, f.nomFiliere, f.idDepartement FROM '. 
            $this->tableFiliere . ' f JOIN ' . $this->tableMatiere . ' m 
            ON m.idFiliere = f.idFiliere JOIN ' . $this->tableProf . ' p ON 
            m.cinProf = :cinProf');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : null;
    }

    /**
     * Cette fonction permet de retourner la liste des matiere
     * @param string $cinProf
     * 
     * @return array|null
     */
    public function getMatiere(string $cinProf) :?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT m.idMatiere, m.nomMatiere FROM '. $this->tableMatiere . 
            ' m JOIN professeur p ON p.cinProf = m.cinProf WHERE m.cinProf= :cinProf');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : null;
    }

    /**
     * Cette fonction permet de retourner la liste des classes
     * @param string $cinProf
     * 
     * @return array
     */
    public function getClasse(string $cinProf):?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT c.idClasse, c.nomClasse, c.idNiveau, c.idFiliere 
            FROM '. $this->tableClasse .' c JOIN ' .$this->tableMatiere .' m ON 
            c.idClasse = m.idClasse JOIN '. $this->tableProf .' p ON m.cinProf = :cinProf');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : null;
    }

    public function setAbsence(array $ArrayAbsence, string $date, array $studentList, int $idMatiere) {
        $query = $this->pdo->prepare('INSERT INTO absence (date, cinEtudiant, idMatiere) VALUES (:date, :cinEtudiant, :idMatiere)');

        foreach($studentList as $student) {
            $cinEtudiant = $student->getCIN();
            
            if (isset($ArrayAbsence[$cinEtudiant]) && $ArrayAbsence[$cinEtudiant] === 'on') {

                $query->execute([
                    'date' => $date,
                    'cinEtudiant' => $cinEtudiant,
                    'idMatiere' => $idMatiere
                ]);
            }
        }
    }

    public function getNbrAbsence(string $cinProf, int $idClass, int $idMatiere):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM '. $this->tableAbsence .' a JOIN '. $this->tableEtudiant .' e ON a.cinEtudiant = e.cinEtudiant 
            JOIN '. $this->tableClasse.' c ON e.idClasse = c.idClasse JOIN '. $this->tableMatiere .' m ON a.idMatiere = m.idMatiere 
            WHERE m.cinProf = :cinProf AND m.idClasse = :idClasse AND m.idMatiere = :idMatiere
        ');

        $query->execute([
            'cinProf' =>$cinProf,
            'idClasse' => $idClass,
            'idMatiere' => $idMatiere
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classAbsents);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : null;
    }

    public function getAbsents(string $cinProf):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM ' . $this->tableAbsence .' a JOIN '. $this->tableEtudiant .' e ON a.cinEtudiant = e.cinEtudiant 
            JOIN '. $this->tableMatiere .' m ON a.idMatiere = m.idMatiere WHERE m.cinProf = :cinProf
        ');

        $query->execute([
            'cinProf' => $cinProf
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classAbsents);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : null;
    }

}