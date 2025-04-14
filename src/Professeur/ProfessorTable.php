<?php

namespace App\Professeur;

use App\Model\Etudiant;
use App\Model\Filiere;
use App\Abstract\Table;
use App\Model\Absents;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\AbsenceEtudiant;

class ProfessorTable extends Table {

    private $listeComplete = [];
    

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
            FROM professeur p JOIN matiere m ON p.cinProf = m.cinProf JOIN 
            classe c ON m.idClasse = c.idClasse JOIN etudiant e ON e.idClasse = c.idClasse WHERE p.cinProf = :cinProf');
            
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
    public function findStudentByClass(int $idClass):array {
        $query = $this->pdo->prepare('
            SELECT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email, e.idClasse FROM 
            etudiant e WHERE e.idClasse = :idClasse');

        $query->execute(['idClasse' => $idClass]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
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
            SELECT e.cinEtudiant, e.nom, e.prenom, e.email, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM absence a JOIN etudiant e ON a.cinEtudiant = e.cinEtudiant JOIN matiere m ON a.idMatiere = m.idMatiere
            JOIN classe c ON e.idClasse = c.idClasse WHERE m.cinProf = :cinProf AND m.idClasse = :idClasse AND m.idMatiere = :idMatiere
            GROUP BY e.cinEtudiant, e.nom, e.cne ORDER BY nbrAbsence DESC
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

    public function getAllStudentList(array $studentList, array $absenceList):array {
        $this->listeComplete = [];
        foreach ($studentList as $etudiant) {
            $absenceTrouvee = false;
            if ($absenceList) {
                foreach ($absenceList as $absent) {
                    if ($etudiant->getCIN() === $absent->getCINEtudiant()) {
                        $this->listeComplete[] = new AbsenceEtudiant(
                            $etudiant->getCIN(),
                            $etudiant->getNom(),
                            $etudiant->getPrenom(),
                            $etudiant->getEmail(),
                            $etudiant->getCNE(),
                            $absent->getNbrAbsence()
                        );
                        $absenceTrouvee = true;
                        break;
                    }
                }
            }
            if (!$absenceTrouvee) {
                $this->listeComplete[] = new AbsenceEtudiant(
                    $etudiant->getCIN(),
                    $etudiant->getNom(),
                    $etudiant->getPrenom(),
                    $etudiant->getEmail(),
                    $etudiant->getCNE(),
                    0
                );
            }
        }
        return $this->listeComplete;
    }

        /**
     * Permet de retourner la liste de tous les étudiants et leurs situations d'absence
     * 
     * @param string $cinProf
     * @return array
     */
    public function getAllStudentAbsenceState(string $cinProf):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom,e.email, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM professeur p JOIN matiere m ON p.cinProf = m.cinProf JOIN 
            absence a ON a.idMatiere = m.idMatiere JOIN etudiant e ON e.cinEtudiant = a.cinEtudiant
            WHERE m.cinProf = :cinProf GROUP BY e.cinEtudiant, e.nom, e.prenom, e.cne ORDER BY nbrAbsence DESC
        ');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Absents::class);
        $result = $query->fetchAll();

        return $result;
    }


}