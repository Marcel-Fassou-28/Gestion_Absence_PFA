<?php

namespace App;

use App\Model\Etudiant;
use App\Model\Filiere;
use App\Abstract\Table;
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

    protected $classEtudiant = Etudiant::class;
    protected $classFiliere = Filiere::class;
    protected $classMatiere = Matiere::class;
    protected $classProf = Professeur::class;
    protected $classClasse = Classe::class;

    /**
     * @var bool|null $errorMessage
     */
    protected $errorMessage;

    /**
     * Cette fonction permet d'obtenir les noms des etudiants par classe 
     * 
     * @param string $class
     * 
     * @return array
     */
    public function findStudentByClass(string $class):array {
        $query = $this->pdo->prepare('
            SELECT e.idEtudiant, e.nom, e.prenom, e.cne, e.cin, e.email FROM 
            '. $this->tableEtudiant .' e JOIN '. $this->tableClasse . '
             c ON e.idClasse = c.idClasse WHERE c.nomClasse = :class');
        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette fonction permet de retourner la liste des filiere
     * @return array
     */
    public function getFiliere(int $idProf) :array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT f.idFiliere, f.nomFiliere, f.idDepartement FROM '. 
            $this->tableFiliere . ' f JOIN ' . $this->tableMatiere . ' m 
            ON m.idFiliere = f.idFiliere JOIN ' . $this->tableProf . ' p ON 
            m.idProf = :idProf');

        $query->execute(['idProf' => $idProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    public function getMatiere(int $idProf) :array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT m.idMatiere, m.nomMatiere FROM '. $this->tableMatiere . ' m JOIN professeur p ON p.idProf = :idProf');
        $query->execute(['idProf' => $idProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    public function getClasse(int $idProf) {
        $query = $this->pdo->prepare('
            SELECT DISTINCT c.idClasse, c.nomClasse, c.idNiveau, c.idFiliere FROM '. $this->tableNiveau
            .' n JOIN ' . $this->tableClasse .' c ON c.idNiveau = n.idNiveau JOIN '. $this->tableFiliere .' f 
            ON f.idFiliere = c.idFiliere JOIN '.$this->tableMatiere .' m ON m.idFiliere = f.idFiliere 
            JOIN '. $this->tableProf .' p ON m.idProf = :idProf');

        $query->execute(['idProf' => $idProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    public function setAbsence(array $ArrayAbsence, string $date, array $studentList, int $idMatiere) {
        $query = $this->pdo->prepare('INSERT INTO absence (date, idEtudiant, idMatiere) VALUES (:date, :idEtudiant, :idMatiere)');

        foreach($studentList as $student) {
            $idEtudiant = $student->getIdEtudiant();
            
            if (isset($ArrayAbsence[$idEtudiant]) && $ArrayAbsence[$idEtudiant] === 'on') {
                $query->execute([
                    'date' => $date,
                    'idEtudiant' => $idEtudiant,
                    'idMatiere' => $idMatiere,
                ]);
            }
        }
    }

}