<?php

namespace App;

use App\Abstract\Table;
use App\Model\Etudiant;
use App\Model\Filiere;
use App\Model\Classe;
use App\Model\Matiere;
use App\Model\Departement;
use App\Model\Utils\Etudiant\AbsenceParMatiere;
use App\Model\Utils\Etudiant\CreneauxProfesseurs;
use App\Model\Utils\Etudiant\DerniereAbsenceEtudiant;

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
     * @return object|null
     */
    public function getFiliere(string $cinEtudiant):?Filiere {
        $query = $this->pdo->prepare('
            SELECT f.nomFiliere FROM etudiant e JOIN '.$this->tableClasse.' c ON e.idClasse = c.idClasse
            JOIN '.$this->tableFiliere.' f ON c.idFiliere = f.idFiliere WHERE e.cinEtudiant = :cinEtudiant');

        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $query->fetch();
        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * Cette methode permet d'obtenir le departement d'un utilisateur
     * @param string $cinEtudiant
     * @return object
     */
    public function getDepartementEtudiant(string $cinEtudiant):?Departement {
    
        $query = $this->pdo->prepare('
            SELECT DISTINCT d.nomDepartement FROM '.$this->tableEtudiant.' e JOIN '.$this->tableClasse.' c 
            ON e.idClasse = c.idClasse JOIN '.$this->tableFiliere.' f ON c.idFiliere = f.idFiliere 
            JOIN '.$this->tableDepartement.' d ON f.idDepartement = d.idDepartement WHERE e.cinEtudiant = :cinEtudiant');
        
        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, \App\Model\Departement::class);
        $result = $query->fetch();

        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * Cette méthode est définie pour obtenir tous les créneaux des professeurs
     * qui enseigne dans une classe où se trouve un étudiant
     * 
     * @param string $cinEtudiant
     * @return array
     */
    public function getAllCreneauxProf(string $cinEtudiant): ?array {
        $query = $this->pdo->prepare('
            SELECT CONCAT(u.nom," ", u.prenom) AS nomProfesseur, cr.jourSemaine, cr.heureDebut, cr.heureFin, m.nomMatiere
            FROM Etudiant e JOIN Classe c ON e.idClasse = c.idClasse JOIN Matiere m ON m.idClasse = c.idClasse
            JOIN Creneaux cr ON cr.idMatiere = m.idMatiere AND cr.cinProf = m.cinProf JOIN Professeur p ON p.cinProf = m.cinProf
            JOIN Utilisateur u ON u.cin = p.cinProf WHERE e.cinEtudiant = :cinEtudiant ORDER BY m.nomMatiere, cr.heureDebut
        ');
        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, CreneauxProfesseurs::class);
        $result = $query->fetchAll();

        $joursSemaine = [
            'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'
        ];

        // Regrouper les créneaux par jour
        $creneauxParJour = array_fill_keys($joursSemaine, []);
        foreach ($result as $creneau) {
            $jour = ucfirst(strtolower($creneau->getJourSemaine()));
            if (in_array($jour, $joursSemaine)) {
                $creneauxParJour[$jour][] = $creneau;
            }
        }

        // Filtrer les jours sans créneaux
        return array_filter($creneauxParJour, function ($creneaux) {
            return !empty($creneaux);
        });

    }

    /**
     * Cette methode permet de retourner les informations générales d'un étudiants
     * sur ça situation académique
     * 
     * @param string $cinEtudiant
     * @return object
     */
    public function getInfoGeneralEtudiant(string $cinEtudiant):?DerniereAbsenceEtudiant {
        $query = $this->pdo->prepare('
            SELECT e.nom, e.prenom, c.nomClasse, f.nomFiliere, d.nomDepartement, m.nomMatiere,
            DATE(a.date) AS dateDerniereAbsence FROM Etudiant e JOIN Classe c ON e.idClasse = c.idClasse
            JOIN Filiere f ON c.idFiliere = f.idFiliere JOIN Departement d ON f.idDepartement = d.idDepartement
            LEFT JOIN Absence a ON a.cinEtudiant = e.cinEtudiant LEFT JOIN Matiere m ON m.idMatiere = a.idMatiere
            WHERE e.cinEtudiant = :cinEtudiant AND a.date = (SELECT MAX(date) FROM Absence WHERE cinEtudiant = e.cinEtudiant)
            LIMIT 1
        ');
        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, DerniereAbsenceEtudiant::class);

        $result = $query->fetch();
        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * Cette methode permet de retourner les informations générales d'un étudiants
     * sur ça situation académique
     * 
     * @param string $cinEtudiant
     * @return object
     */
    public function getInfoGeneralEtudiantWithoutLastAbsence(string $cinEtudiant):?DerniereAbsenceEtudiant {
        $query = $this->pdo->prepare('
            SELECT e.nom, e.prenom, c.nomClasse, f.nomFiliere, d.nomDepartement
            FROM Etudiant e JOIN Classe c ON e.idClasse = c.idClasse
            JOIN Filiere f ON c.idFiliere = f.idFiliere JOIN Departement d ON f.idDepartement = d.idDepartement
            WHERE e.cinEtudiant = :cinEtudiant LIMIT 1;
        ');
        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, DerniereAbsenceEtudiant::class);

        $result = $query->fetch();
        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * Cette méthode permet de retourner les statistique des absences d'un etudiant
     * par matières qu'il suit dans sa classe
     * 
     * @param string $cinEtudiant
     * @return array
     */
    public function getStatistiqueAbsenceEtudiant(string $cinEtudiant):?array {
        $query = $this->pdo->prepare('
            SELECT m.nomMatiere, COUNT(a.idAbsence) AS nombreAbsences
            FROM Absence a JOIN Matiere m ON a.idMatiere = m.idMatiere
            WHERE a.cinEtudiant = :cinEtudiant GROUP BY m.nomMatiere
            ORDER BY nombreAbsences DESC
        ');
        $query->execute(['cinEtudiant' => $cinEtudiant]);
        $query->setFetchMode(\PDO::FETCH_CLASS, AbsenceParMatiere::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }

    public function findByCin(string $cin): ?Etudiant {
        $query = $this->pdo->prepare("SELECT * FROM etudiant WHERE cinEtudiant = :cin");
        $query->execute(['cin' => $cin]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $etudiant = $query->fetch();
        return $etudiant ?: null;
    }

    public function getAllByClasse(int $idClasse): array {
        $query = $this->pdo->prepare("SELECT * FROM etudiant WHERE idClasse = :idClasse ORDER BY nom, prenom");
        $query->execute(['idClasse' => $idClasse]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);

        $result = $query->fetchAll();
        return count($result) > 0 ? $result : $result;
    }
        

}