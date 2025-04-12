<?php

namespace App;

use App\Model\Etudiant;
use App\Model\Filiere;
use App\Abstract\Table;
use App\Model\Absence;
use App\Model\Absents;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\Professeur;

class ProfessorTable extends Table {

    /**
     * @var bool|null $errorMessage
     */
    protected $errorMessage;
    

    /**
     * Cette methode permet d'obtenir les noms des etudiants par classe 
     * en passant le cin du professeur en paramètre
     * 
     * @param string $cinProf
     * @return array
     */
    public function findStudent(string $cinProf):array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email
            FROM etudiant e JOIN classe c ON e.idClasse = c.idClasse JOIN 
            matiere m ON c.idClasse = m.idMatiere WHERE m.cinProf = :cinProf');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette methode permet d'obtenir les noms des etudiants d'une classe 
     * 
     * @param string $class
     * @return array
     */
    public function findStudentByClass(string $class):array {
        $query = $this->pdo->prepare('
            SELECT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email FROM 
            etudiant e JOIN classe c ON e.idClasse = c.idClasse WHERE c.nomClasse = :class');

        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette methode permet de retourner la liste des filiere
     * en passant le cin du Prof en paramètre
     * 
     * @param string $cinProf
     * @return object|null
     */
    public function getCurrentFiliere(string $cinProf):?Filiere {
        $query = $this->pdo->prepare('
            SELECT f.* FROM creneaux c JOIN matiere m ON c.idMatiere = m.idMatiere
            JOIN classe cl ON m.idClasse = cl.idClasse JOIN filiere f ON cl.idFiliere = f.idFiliere
            WHERE c.cinProf = :cinProf AND CURRENT_TIME BETWEEN c.heureDebut AND c.heureFin');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Filiere::class);
        $result = $query->fetch();

        return $result;
    }

    /**
     * Cette methode permet de retourner la liste des matiere
     * en passant le cin d'un professeur en paramètre
     * 
     * @param string $cinProf
     * @return object|null
     */
    public function getCurrentMatiere(string $cinProf):?Matiere {
        $query = $this->pdo->prepare('
            SELECT m.* FROM creneaux c JOIN matiere m ON c.idMatiere = m.idMatiere
            JOIN classe cl ON m.idClasse = cl.idClasse JOIN filiere f ON cl.idFiliere = f.idFiliere
            WHERE c.cinProf = :cinProf AND CURRENT_TIME BETWEEN c.heureDebut AND c.heureFin');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
        $result = $query->fetch();

        return $result;
    }

    /**
     * Cette methode permet de retourner la liste des classes dans lesquelles
     * un professeur enseigne, ici le cin du prof en question est passé en paramètre
     * 
     * @param string $cinProf
     * @return object
     */
    public function getCurrentClasse(string $cinProf): ?Classe {
        $query = $this->pdo->prepare('
            SELECT cl.* FROM creneaux c JOIN matiere m ON c.idMatiere = m.idMatiere
            JOIN classe cl ON m.idClasse = cl.idClasse JOIN filiere f ON cl.idFiliere = f.idFiliere
            WHERE c.cinProf = :cinProf AND CURRENT_TIME BETWEEN c.heureDebut AND c.heureFin');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
        $result = $query->fetch();

        return $result;
    }

    /**
     * Cette methode permet de soumettre la liste des absents lors d'une scéance
     * 
     * @param string $date Qui est la date à laquelle est effectué l'absence
     * @param array $ArrayAbsence Contient les cin des etudiants absents lors du cours
     * @param array $studentList Contient la liste des étudiants enseignés par le prof
     * @param int $idMatiere L'id de la matière enseignée par le professeur
     * @return array
     */
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

    /**
     * Cette methode permet de retourner la liste des eleves qui ce sont absentés au moins une fois
     * Mais uniquement les élèves enseignés par le professeur
     * 
     * @param string $cinProf Le cin du professeur
     * @param int $idClass L'id de la classe enseignée
     * @param int $idMatiere L'id de la matière enseignée
     * @return array
     */
    public function getNbrAbsence(string $cinProf, int $idClass, int $idMatiere):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM absence a JOIN etudiant e ON a.cinEtudiant = e.cinEtudiant 
            JOIN classe c ON e.idClasse = c.idClasse JOIN matiere m ON a.idMatiere = m.idMatiere 
            WHERE m.cinProf = :cinProf AND m.idClasse = :idClasse AND m.idMatiere = :idMatiere
        ');

        $query->execute([
            'cinProf' =>$cinProf,
            'idClasse' => $idClass,
            'idMatiere' => $idMatiere
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Absents::class);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : null;
    }

    /**
     * Cette methode permet de retourner la liste des absents liés le professeur en question
     * 
     * @param string $cinProf
     * @return array
     */
    public function getAbsents(string $cinProf):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM absence a JOIN etudiant e ON a.cinEtudiant = e.cinEtudiant 
            JOIN matiere m ON a.idMatiere = m.idMatiere WHERE m.cinProf = :cinProf
        ');

        $query->execute([
            'cinProf' => $cinProf
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Absents::class);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : null;
    }

    public function studentCurrent(string $cinProf) {
        $query = $this->pdo->prepare('
            SELECT DISTINCT e.* FROM etudiant e JOIN classe cl ON e.idClasse = cl.idClasse 
            JOIN matiere m ON cl.idClasse = m.idClasse JOIN creneaux cr ON m.idMatiere = cr.idMatiere 
            JOIN professeur p ON cr.cinProf = p.cinProf WHERE p.cinProf = :cinProf 
            AND CURRENT_TIME BETWEEN cr.heureDebut AND cr.heureFin;
        ');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : null;
    }

    public function getCreneau(string $cinProf) {
        $query = $this->pdo->prepare('
            SELECT c.* FROM creneaux c WHERE c.cinProf = :cinProf 
            AND CURRENT_TIME BETWEEN c.heureDebut AND c.heureFin');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, \App\Model\Creneaux::class);
        $result = $query->fetch();

        return $result;
    }

    public function getFiliere(string $cinProf) :?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT f.idFiliere, f.nomFiliere,f.alias, f.idDepartement FROM filiere f JOIN matiere m 
            ON m.idFiliere = f.idFiliere JOIN professeur p ON 
            m.cinProf = :cinProf');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Filiere::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : null;
    }

    public function getMatiere(string $cinProf) :?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT m.idMatiere, m.nomMatiere FROM matiere m 
            JOIN professeur p ON p.cinProf = m.cinProf WHERE m.cinProf= :cinProf');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : null;
    }

    public function getClasse(string $cinProf): ?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT c.idClasse, c.nomClasse, c.idNiveau, c.idFiliere 
            FROM classe c JOIN matiere m ON 
            c.idClasse = m.idClasse JOIN professeur p ON m.cinProf = :cinProf');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : null;
    }

}