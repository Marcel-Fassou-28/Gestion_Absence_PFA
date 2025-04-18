<?php

namespace App\Professeur;

use App\Model\Etudiant;
use App\Model\Filiere;
use App\Abstract\Table;
use App\Model\Absents;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\AbsenceEtudiant;
use App\Model\ListePresence;
use App\Model\Utils\InfoAbsenceEtudiant;
use App\Model\Utils\EtudiantsAbsents;


class ProfessorTable extends Table {

    private $listeComplete = [];
    private $infoAbsenceEtudiant = [];
    

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
     * Cette méthode permet de retourner la liste des absents liés au professeur en question
     * 
     * @param string $cinProf
     * @return array|null
     */
    public function getAbsents(string $cinProf): ?array
    {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, c.nomClasse, m.nomMatiere,
            DATE(a.date) AS dateAbsence, cr.heureDebut, cr.heureFin,
            FLOOR(COUNT(*) OVER (PARTITION BY e.cinEtudiant, m.idMatiere) / 3) AS nombreAbsences
            FROM Absence a 
            JOIN Etudiant e ON e.cinEtudiant = a.cinEtudiant
            JOIN Classe c ON c.idClasse = e.idClasse 
            JOIN Matiere m ON m.idMatiere = a.idMatiere
            JOIN Creneaux cr ON cr.idMatiere = m.idMatiere AND cr.cinProf = m.cinProf
            WHERE m.cinProf = :cinProf 
            ORDER BY e.nom, e.prenom, m.nomMatiere, dateAbsence
        ');
        $query->execute([
            'cinProf' => $cinProf
        ]);

        $query->setFetchMode(\PDO::FETCH_CLASS, InfoAbsenceEtudiant::class);
        $result = $query->fetchAll();

        return $this->getInfoAbsenceByStudent($result);
    }

    /**
     * Organise les absences par étudiant
     * 
     * @param array $infoAbsenceArray
     * @return array
     */
    public function getInfoAbsenceByStudent(array $infoAbsenceArray): array
    {
        $this->infoAbsenceEtudiant = [];

        foreach ($infoAbsenceArray as $infoAbsence) {
            $cin = $infoAbsence->getCinEtudiant();
            
            // Créer un nouvel objet seulement si l'étudiant n'existe pas
            if (!isset($this->infoAbsenceEtudiant[$cin])) {
                $this->infoAbsenceEtudiant[$cin] = new EtudiantsAbsents(
                    $infoAbsence->getNom(),
                    $infoAbsence->getPrenom(),
                    $infoAbsence->getCne(),
                    $infoAbsence->getNomClasse(),
                    $infoAbsence->getNomMatiere(),
                    $infoAbsence->getNombreAbsences()
                );
            }
            
            // Ajouter l'absence
            $absenceKey = $infoAbsence->getDateAbsence() . ' ' . $infoAbsence->getHeureDebut() . '-' . $infoAbsence->getHeureFin();
            $this->infoAbsenceEtudiant[$cin]->addAbsence($absenceKey);
        }

        // Trier les absences pour chaque étudiant
        foreach ($this->infoAbsenceEtudiant as $etudiant) {
            $etudiant->sortAbsences();
        }

        return $this->infoAbsenceEtudiant;
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

    public function sendListPresence(ListePresence $list):bool {
        $query = $this->pdo->prepare('
            INSERT INTO listepresence(cinProf, classe ,nomFichierPresence) VALUES (:cinProf, :classe, :nomFichierPresence)
        ');
        if ($list !== null) {
            $query->execute([
                'cinProf' => $list->getCINProf(),
                'classe' => $list->getClasse(),
                'nomFichierPresence' => $list->getNomFichierPresence()
            ]);
            return true;
        }else {
            return false;
        }

    }


}