<?php

namespace App\Professeur;

use App\Model\Etudiant;
use App\Model\Filiere;
use App\Abstract\Table;
use App\Model\Absents;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\AbsenceEtudiant;
use App\Model\Departement;
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
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function findStudent(string $cinProf, int $line = 0, int $offset = 0):array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email, e.idClasse, c.nomClasse
            FROM professeur p JOIN matiere m ON p.cinProf = m.cinProf JOIN 
            classe c ON m.idClasse = c.idClasse JOIN etudiant e ON e.idClasse = c.idClasse WHERE p.cinProf = :cinProf
            LIMIT '. $line . ' OFFSET '. $offset .'');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette methode permet d'obtenir les noms des etudiants d'une classe 
     * avec option de pagination
     * 
     * @param int $idClass
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function findStudentByClass(int $idClass, int $line = 0 , $offset = 0):array {
        $query = $this->pdo->prepare('
            SELECT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email, e.idClasse, c.nomClasse FROM 
            etudiant e JOIN classe c ON e.idClasse = c.idClasse WHERE e.idClasse = :idClasse LIMIT '. $line . ' OFFSET ' . $offset .'
            ');

        $query->execute(['idClasse' => $idClass]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette methode permet d'obtenir les noms des etudiants d'une classe 
     * 
     * @param int $idClass
     * @return array
     */
    public function findStudentByClassID(int $idClass):array {
        $query = $this->pdo->prepare('
            SELECT e.idEtudiant, e.nom, e.prenom, e.cne, e.cinEtudiant, e.email, e.idClasse, c.nomClasse FROM 
            etudiant e JOIN classe c ON e.idClasse = c.idClasse WHERE e.idClasse = :idClasse
            ');

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
     * @return bool
     */
    public function setAbsence(array $ArrayAbsence, string $date, array $studentList, int $idMatiere):bool {
        $query = $this->pdo->prepare('INSERT INTO absence (date, cinEtudiant, idMatiere) VALUES (:date, :cinEtudiant, :idMatiere)');

        foreach($studentList as $student) {
            $cinEtudiant = $student->getCIN();
            
            if (isset($ArrayAbsence[$cinEtudiant]) && $ArrayAbsence[$cinEtudiant] === 'on') {

                $query->execute([
                    'date' => $date,
                    'cinEtudiant' => $cinEtudiant,
                    'idMatiere' => $idMatiere
                ]);
                return true;
            }
        }
        return false;
    }

    /**
     * Cette methode permet generer une url avec comme un formulaire soumit avec get
     * 
     * @param mixed $col
     * @param mixed $val
     * @return string
     */
    public function test($col, $val): string
    {
        return http_build_query(array_merge($_GET, [$col => $val]));
    }

    /**
     * Cette methode permet de retourner la liste des eleves qui ce sont absentés au moins une fois
     * Mais uniquement les élèves enseignés par le professeur
     * 
     * @param string $cinProf Le cin du professeur
     * @param int $idClass L'id de la classe enseignée
     * @param int $idMatiere L'id de la matière enseignée
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getNbrAbsence(string $cinProf, int $idClass, int $idMatiere, int $line = 0, int $offset = 0):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.email, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM absence a JOIN etudiant e ON a.cinEtudiant = e.cinEtudiant JOIN matiere m ON a.idMatiere = m.idMatiere
            JOIN classe c ON e.idClasse = c.idClasse WHERE m.cinProf = :cinProf AND m.idClasse = :idClasse AND m.idMatiere = :idMatiere
             GROUP BY e.cinEtudiant, e.nom, e.cne ORDER BY nbrAbsence DESC LIMIT ' .$line .' OFFSET '. $offset.'
        ');

        $query->execute([
            'cinProf' =>$cinProf,
            'idClasse' => $idClass,
            'idMatiere' => $idMatiere
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Absents::class);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : [];
    }

    /**
     * Cette methode permet de retourner la liste des eleves qui ce sont absentés au moins une fois
     * Mais uniquement les élèves enseignés par le professeur par classe
     * 
     * @param string $cinProf Le cin du professeur
     * @param int $idClass L'id de la classe enseignée
     * @param int $idMatiere L'id de la matière enseignée
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getNbrAbsenceByClasse(string $cinProf, int $idClass, int $line = 0, int $offset = 0):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.email, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM absence a JOIN etudiant e ON a.cinEtudiant = e.cinEtudiant JOIN matiere m ON a.idMatiere = m.idMatiere
            JOIN classe c ON e.idClasse = c.idClasse WHERE m.cinProf = :cinProf AND m.idClasse = :idClasse 
            GROUP BY e.cinEtudiant, e.nom, e.cne ORDER BY nbrAbsence DESC LIMIT '.$line .' OFFSET '. $offset . '
        ');

        $query->execute([
            'cinProf' =>$cinProf,
            'idClasse' => $idClass
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Absents::class);
        $result = $query->fetchAll();
        return count($result) > 0 ? $result : [];
    }

    /**
     * Cette méthode permet de retourner la liste des absents liés au professeur en question
     * 
     * @param string $cinProf
     * @param int $line
     * @param int $offset
     * @return array|null
     */
    public function getAbsents(string $cinProf, int $line = 0, $offset = 0): ?array
    {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, c.nomClasse, m.nomMatiere,
            DATE(a.date) AS dateAbsence, cr.heureDebut, cr.heureFin
            FROM Absence a 
            JOIN Etudiant e ON e.cinEtudiant = a.cinEtudiant
            JOIN Classe c ON c.idClasse = e.idClasse 
            JOIN Matiere m ON m.idMatiere = a.idMatiere
            JOIN Creneaux cr ON cr.idMatiere = m.idMatiere AND cr.cinProf = m.cinProf
            WHERE m.cinProf = :cinProf 
            ORDER BY e.nom, e.prenom, m.nomMatiere, dateAbsence LIMIT '. $line.' OFFSET '. $offset.'
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

            // Créer une entrée s'il n'y en a pas encore
            if (!isset($this->infoAbsenceEtudiant[$cin])) {
                $this->infoAbsenceEtudiant[$cin] = new EtudiantsAbsents(
                    $infoAbsence->getNom(),
                    $infoAbsence->getPrenom(),
                    $infoAbsence->getCne(),
                    $infoAbsence->getNomClasse(),
                    $infoAbsence->getNomMatiere()
                );
            }

            // Ajout de l'absence à la liste
            $absenceKey = $infoAbsence->getDateAbsence() . ' ' . $infoAbsence->getHeureDebut() . '-' . $infoAbsence->getHeureFin();
            $this->infoAbsenceEtudiant[$cin]->addAbsence($absenceKey);
        }

        // Trie les absences par étudiant
        foreach ($this->infoAbsenceEtudiant as $etudiant) {
            $etudiant->sortAbsences();
        }

        return $this->infoAbsenceEtudiant;
    }

    /**
     * Cette méthode permet de retourner la liste des absents liés au professeur en question
     * by classe 
     * 
     * @param string $cinProf
     * @param int $idClass
     * @param int $line
     * @param int $offset
     * 
     * @return array
     */
    public function getAbsentsByClasse(string $cinProf, int $idClass, int $line = 0, int $offset = 0): array
    {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, c.nomClasse, m.nomMatiere,
            DATE(a.date) AS dateAbsence, cr.heureDebut, cr.heureFin
            FROM Absence a 
            JOIN Etudiant e ON e.cinEtudiant = a.cinEtudiant
            JOIN Classe c ON c.idClasse = e.idClasse 
            JOIN Matiere m ON m.idMatiere = a.idMatiere
            JOIN Creneaux cr ON cr.idMatiere = m.idMatiere AND cr.cinProf = m.cinProf
            WHERE m.cinProf = :cinProf AND e.idClasse = :idClasse  
            ORDER BY e.nom, e.prenom, m.nomMatiere, dateAbsence LIMIT '. $line.' OFFSET '. $offset.'
        ');
        $query->execute([
            'cinProf' => $cinProf,
            'idClasse' => $idClass
        ]);

        $query->setFetchMode(\PDO::FETCH_CLASS, InfoAbsenceEtudiant::class);
        $result = $query->fetchAll();

        return $this->getInfoAbsenceByStudent($result);
    }

    /**
     * Cette méthode permet de retourner la liste des absents liés au professeur en question
     * by classe et by par matiere
     * 
     * @param string $cinProf
     * @param int $idClass
     * @param int $idMatiere
     * @param int $line
     * @param int $offset
     * 
     * @return array
     */
    public function getAbsentsByMatiereClasse(string $cinProf, int $idClass, int $idMatiere, int $line = 0, int $offset = 0): array
    {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom, e.cne, c.nomClasse, m.nomMatiere,
            DATE(a.date) AS dateAbsence, cr.heureDebut, cr.heureFin
            FROM Absence a 
            JOIN Etudiant e ON e.cinEtudiant = a.cinEtudiant
            JOIN Classe c ON c.idClasse = e.idClasse 
            JOIN Matiere m ON m.idMatiere = a.idMatiere
            JOIN Creneaux cr ON cr.idMatiere = m.idMatiere AND cr.cinProf = m.cinProf
            WHERE m.cinProf = :cinProf AND (e.idClasse = :idClasse AND m.idMatiere = :idMatiere) 
            ORDER BY e.nom, e.prenom, m.nomMatiere, dateAbsence LIMIT '. $line.' OFFSET '. $offset.'
        ');
        $query->execute([
            'cinProf' => $cinProf,
            'idClasse' => $idClass,
            'idMatiere' => $idMatiere
        ]);

        $query->setFetchMode(\PDO::FETCH_CLASS, InfoAbsenceEtudiant::class);
        $result = $query->fetchAll();

        return $this->getInfoAbsenceByStudent($result);
    }

    public function getFiliere(string $cinProf) :?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT f.idFiliere, f.nomFiliere,f.alias, f.idDepartement FROM filiere f JOIN matiere m 
            ON m.idFiliere = f.idFiliere JOIN professeur p ON 
            m.cinProf = :cinProf');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Filiere::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }


    public function getMatiere(string $cinProf) :?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT m.idMatiere, m.nomMatiere FROM matiere m 
            JOIN professeur p ON p.cinProf = m.cinProf WHERE m.cinProf= :cinProf');
            
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette methode permet de recupérer les informations sur la classe enseignée
     * par un professeur
     * 
     * @param string $cinProf
     * @return array
     */
    public function getClasse(string $cinProf): ?array {
        $query = $this->pdo->prepare('
            SELECT DISTINCT c.idClasse, c.nomClasse, c.idNiveau, c.idFiliere 
            FROM classe c JOIN matiere m ON 
            c.idClasse = m.idClasse JOIN professeur p ON m.cinProf = :cinProf');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
        $result = $query->fetchAll();

        return count($result) != 0 ? $result : [];
    }


    /**
     * Cette méthode permet de regrouper les informations d'absence des étudiants
     * aux etudiant
     * 
     * @param array $studentList
     * @param array $absenceList
     * 
     * @return array $listComplete Association entre les infos d'absence et les étudiants
     */
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
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getAllStudentAbsenceState(string $cinProf, int $line =0 , int $offset = 0):?array {
        $query = $this->pdo->prepare('
            SELECT e.cinEtudiant, e.nom, e.prenom,e.email, e.cne, COUNT(a.idAbsence) AS nbrAbsence
            FROM professeur p JOIN matiere m ON p.cinProf = m.cinProf JOIN 
            absence a ON a.idMatiere = m.idMatiere JOIN etudiant e ON e.cinEtudiant = a.cinEtudiant
            WHERE m.cinProf = :cinProf GROUP BY e.cinEtudiant, e.nom, e.prenom, e.cne ORDER BY nbrAbsence DESC LIMIT '. $line.' OFFSET '. $offset.' 
        ');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Absents::class);
        $result = $query->fetchAll();

        return count($result) > 0 ?  $result : [];
    }

    /**
     * Cette méthode permet d'envoyer les informations de présence des étudiants
     * 
     * @param ListePresence $list
     * @return bool Si la requete a bien été exécuté ou pas
     */
    public function sendListPresence(ListePresence $list):bool {
        $query = $this->pdo->prepare('
            INSERT INTO listepresence(cinProf, classe ,nomFichierPresence, matiere) VALUES (:cinProf, :classe, :nomFichierPresence, :matiere)
        ');
        if ($list !== null) {
            $query->execute([
                'cinProf' => $list->getCINProf(),
                'classe' => $list->getClasse(),
                'nomFichierPresence' => $list->getNomFichierPresence(),
                'matiere' => $list->getMatiere()
            ]);
            return true;
        }else {
            return false;
        }

    }


}