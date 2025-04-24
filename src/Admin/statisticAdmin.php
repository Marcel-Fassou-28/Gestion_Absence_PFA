<?php


namespace App\Admin;

use App\Abstract\Table;
use App\Model\Administrateur;
use App\Model\Classe;
use App\Model\Utils\Admin\ClasseFiliere;
use App\Model\Utils\Admin\DerniereAbsences;
use App\Model\Utils\Admin\InformationActifs;
use App\Model\Utils\Admin\ListPresenceStat;
use App\Model\Utils\Admin\StatisticFiliere;
use App\Model\Utils\Admin\MatiereProf;

class StatisticAdmin extends Table {

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
     * Cette méthode renvoi les statistiques des différentes filières
     * Enseignées à l'école (Nom filière, Effectifs, Nom d'Absents)
     * 
     * @return array
     */
    public function getStatisticFiliere():array {
        $query = $this->pdo->prepare('
            SELECT f.nomFiliere, COUNT(DISTINCT e.idEtudiant) AS totalEtudiants, COUNT(a.idAbsence) AS totalAbsences
            FROM Filiere f LEFT JOIN Classe c ON f.idFiliere = c.idFiliere LEFT JOIN Etudiant e ON c.idClasse = e.idClasse
            LEFT JOIN Absence a ON e.cinEtudiant = a.cinEtudiant GROUP BY f.idFiliere, f.nomFiliere ORDER BY totalEtudiants DESC
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, StatisticFiliere::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }


    /**
     * Cette méthode permet de renvoyer les informations générales sur l'Etablissement
     * Total inscrit, nombre d'Absents,nombre professeurs actifs donnant un cours selon le créneau
     * et le nombre de matières enseignées
     * 
     * @return InformationActifs
     */
    public function getInformationGenerale():?InformationActifs {
        $query = $this->pdo->prepare('SELECT (SELECT COUNT(*) FROM Etudiant) AS totalInscrits,
            (SELECT COUNT(*) FROM Absence) AS totalAbsents, (SELECT COUNT(DISTINCT cinProf)
            FROM Creneaux WHERE CURTIME() BETWEEN heureDebut AND heureFin AND jourSemaine = 
                CASE DAYOFWEEK(CURDATE())
                    WHEN 2 THEN \'Lundi\'
                    WHEN 3 THEN \'Mardi\'
                    WHEN 4 THEN \'Mercredi\'
                    WHEN 5 THEN \'Jeudi\'
                    WHEN 6 THEN \'Vendredi\'
                    WHEN 7 THEN \'Samedi\'
                    ELSE NULL
                END) AS professeursActifsActuellement, ( SELECT COUNT(DISTINCT c1.cinProf)
                    FROM Creneaux c1 JOIN Matiere m ON c1.idMatiere = m.idMatiere JOIN Classe cl ON m.idClasse = cl.idClasse
                    WHERE CURTIME() BETWEEN c1.heureDebut AND c1.heureFin AND c1.jourSemaine = 
                CASE DAYOFWEEK(CURDATE())
                    WHEN 2 THEN \'Lundi\'
                    WHEN 3 THEN \'Mardi\'
                    WHEN 4 THEN \'Mercredi\'
                    WHEN 5 THEN \'Jeudi\'
                    WHEN 6 THEN \'Vendredi\'
                    WHEN 7 THEN \'Samedi\'
                    ELSE NULL
                END AND NOT EXISTS ( SELECT 1 FROM ListePresence lp WHERE  lp.cinProf = c1.cinProf
                    AND DATE(lp.date) = CURDATE()
                    AND lp.classe = cl.nomClasse ) ) AS professeursAbsentsActuellement
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, InformationActifs::class);
        $result = $query->fetch();

        return $result ?? null;
    }

    /**
     * Retourne les dernières Absences Effectués par les professeur
     * 
     * @return array
     */
    public function getLastAbsenceSend():array {
        $query = $this->pdo->prepare('
            SELECT cl.nomClasse, m.nomMatiere,COUNT(cl.nomClasse) AS nombreAbsents FROM Absence a JOIN Matiere m ON a.idMatiere = m.idMatiere
            JOIN Classe cl ON m.idClasse = cl.idClasse GROUP BY cl.nomClasse ORDER BY a.date DESC LIMIT 20;
        ');
        /*
         SELECT cl.nomClasse, m.nomMatiere, CONCAT(c.heureDebut, " - ", c.heureFin) AS creneau, 
            COUNT(a.idAbsence) AS nombreAbsents FROM Absence a JOIN Matiere m ON a.idMatiere = m.idMatiere
            JOIN Classe cl ON m.idClasse = cl.idClasse JOIN Creneaux c ON c.idMatiere = m.idMatiere AND c.cinProf = m.cinProf
            WHERE TIME(a.date) BETWEEN c.heureDebut AND c.heureFin GROUP BY cl.nomClasse, m.nomMatiere, c.heureDebut, c.heureFin
            ORDER BY a.date DESC LIMIT 10
        */

        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, DerniereAbsences::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }

    /**
     * Cette methode renvoi le nombre de prof ayant soumis un justificatif et
     * permet de savoir s'il y'a un justificatif soumis
     * 
     * @return ListPresenceStat
     */
    public function getIfAbsenceByFile():ListPresenceStat {

        $query = $this->pdo->prepare('
            SELECT COUNT(*) AS nombreListesSoumisesAujourdHui FROM ListePresence
            WHERE DATE(date) = CURDATE()
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, ListPresenceStat::class);
        $result = $query->fetch();

        return $result;
    }

    /**
     * Cette methode renvoi la liste de toutes les matières de l'établissement
     * 
     * @return array
     */
    public function getAllMatiere():?array {
        $query = $this->pdo->prepare('
            SELECT m.idMatiere ,m.nomMatiere, p.cinProf, p.nom as nomProf, p.prenom as prenomProf, c.nomClasse FROM professeur p 
            JOIN matiere m ON m.cinProf = p.cinProf JOIN classe c ON m.idClasse = c.idClasse
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, MatiereProf::class);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Cette methode renvoi la liste de toutes les matières de l'établissement
     * à travers le nom d'une filiere
     * 
     * @param string $nomFiliere
     * @param int $line Pour la pagination
     * @param int $offset Pour la pagination
     * @return array
     */
    public function getAllMatiereByFilieres(string $nomFiliere, int $line = 0,int $offset= 0):?array {
        if($line !== 0) {
            $query = $this->pdo->prepare('
            SELECT m.idMatiere ,m.nomMatiere, p.cinProf, p.nom as nomProf, p.prenom as prenomProf, c.nomClasse FROM professeur p 
            JOIN matiere m ON m.cinProf = p.cinProf JOIN classe c ON m.idClasse = c.idClasse JOIN 
            filiere f  ON f.idFiliere = c.idFiliere WHERE f.nomFiliere = :nomFiliere LIMIT '. $line . ' OFFSET ' . $offset . ' 
        ');
        } else {
            $query = $this->pdo->prepare('
            SELECT m.idMatiere ,m.nomMatiere, p.cinProf, p.nom as nomProf, p.prenom as prenomProf, c.nomClasse FROM professeur p 
            JOIN matiere m ON m.cinProf = p.cinProf JOIN classe c ON m.idClasse = c.idClasse JOIN 
            filiere f  ON f.idFiliere = c.idFiliere WHERE f.nomFiliere = :nomFiliere 
        ');
        }

        $query->execute([
            'nomFiliere' => $nomFiliere
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, MatiereProf::class);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Cette methode renvoi la liste de toutes les matières de l'établissement
     * à travers le nom d'une filiere et d'une classe
     * 
     * @param string $nomFiliere
     * @param string $nomClasse
     * @param int $line Pour la pagination
     * @param int $offset Pour la pagination
     * @return array
     */
    public function getAllMatiereByFilieresClasses(string $nomFiliere, string $nomClasse, int $line , int $offset):?array {
        if ($line !== 0) {
            $query = $this->pdo->prepare('
            SELECT m.idMatiere ,m.nomMatiere, p.cinProf, p.nom as nomProf, p.prenom as prenomProf, c.nomClasse 
            FROM filiere f JOIN classe c ON f.idFiliere = c.idFiliere 
            JOIN matiere m ON c.idClasse = m.idClasse JOIN professeur p ON m.cinProf = p.cinProf 
            WHERE f.nomFiliere = :nomFiliere AND c.nomClasse = :nomClasse LIMIT ' . $line . ' OFFSET ' . $offset . ' 
        ');
        } else {
        $query = $this->pdo->prepare('
            SELECT m.idMatiere ,m.nomMatiere, p.cinProf, p.nom as nomProf, p.prenom as prenomProf, c.nomClasse 
            FROM filiere f JOIN classe c ON f.idFiliere = c.idFiliere 
            JOIN matiere m ON c.idClasse = m.idClasse JOIN professeur p ON m.cinProf = p.cinProf 
            WHERE f.nomFiliere = :nomFiliere AND c.nomClasse = :nomClasse
        ');
        }
        $query->execute([
            'nomFiliere' => $nomFiliere,
            'nomClasse' => $nomClasse
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, MatiereProf::class);
        $result = $query->fetchAll();

        return $result;
    }


    /**
     * Cette méthode renvoi la liste de tous les administrateurs du site
     * 
     * @return array
     */
    public function getAllAdministrateur():array {
        $query = $this->pdo->prepare('
            SELECT * FROM administrateur
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Cette méthode renvoi la liste de toutes les classes de l'etablissement
     * 
     * @return array
     */
    public function getAllClasses():array {
        $query = $this->pdo->prepare('
            SELECT c.*, n.nomNiveau, f.nomFiliere FROM niveau n JOIN classe c ON n.idNiveau = c.idNiveau 
            JOIN filiere f ON f.idFiliere = c.idFiliere 
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, ClasseFiliere::class);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Cette méthode renvoi la liste de toutes les classes de l'etablissement
     * à travers l'année et/ou la filiere
     * 
     * @param string $niveau
     * @param string $filiere
     * @return array
     */
    public function getAllClassesByLevelFiliere(string $niveau = "", string $filiere = ""):array {
        if(!empty ($niveau)) {
            $query = $this->pdo->prepare('
                SELECT c.*, n.nomNiveau, f.nomFiliere FROM niveau n JOIN classe c ON n.idNiveau = c.idNiveau 
                JOIN filiere f ON f.idFiliere = c.idFiliere WHERE n.nomNiveau = :nomNiveau
           ');
           $query->execute(['nomNiveau' => $niveau]);

        } elseif(!empty($filiere)) {
            $query = $this->pdo->prepare('
                SELECT c.*, n.nomNiveau, f.nomFiliere FROM niveau n JOIN classe c ON n.idNiveau = c.idNiveau 
                JOIN filiere f ON f.idFiliere = c.idFiliere WHERE f.nomFiliere = :nomFiliere
            ');
            $query->execute(['nomFiliere' => $filiere]);

        } elseif(!empty($niveau) && !empty($filiere)) {
            $query = $this->pdo->prepare('
                SELECT c.*, n.nomNiveau, f.nomFiliere FROM niveau n JOIN classe c ON n.idNiveau = c.idNiveau 
                JOIN filiere f ON f.idFiliere = c.idFiliere WHERE f.nomFiliere = :nomFiliere AND n.nomNiveau = :nomNiveau
            ');
            $query->execute(['nomFiliere' => $filiere, 'nomNiveau' => $niveau]);

        } else {
            $query = $this->pdo->prepare('
                SELECT c.*, n.nomNiveau, f.nomFiliere FROM niveau n JOIN classe c ON n.idNiveau = c.idNiveau 
                JOIN filiere f ON f.idFiliere = c.idFiliere 
            ');
            $query->execute();
        }
        $query->setFetchMode(\PDO::FETCH_CLASS, ClasseFiliere::class);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Cette méthode permet de retourner la liste des filieres
     * 
     * @return array
     */
    public function getListeFiliere():?array {

        $query = $this->pdo->prepare('
            SELECT nomFiliere FROM filiere
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, ClasseFiliere::class);
        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Cette méthode permet de retourner la liste des niveau
     * 
     * @return array
     */
    public function getListeNiveau():?array {

        $query = $this->pdo->prepare('
            SELECT nomNiveau FROM niveau
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, ClasseFiliere::class);
        $result = $query->fetchAll();

        return $result;
    }
}