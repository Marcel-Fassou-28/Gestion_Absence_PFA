<?php

namespace App\Professeur;

use App\Abstract\Table;
use App\Model\Filiere;
use App\Model\Matiere;
use App\Model\Classe;
use App\Model\Etudiant;
use App\Model\Creneaux;


/**
 * Cette classe sert à l'obtention des information en temps
 * reel selon le créneau d'un professeur
 */
class CurrentInfo extends Table {

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
     * Cette méthode retourne la liste des étudiants enseignés
     * par un professeur
     * 
     * @param string $cinProf
     * @return array|null
     */
    public function getCurrentStudentList(string $cinProf):?array {
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

    /**
     * Cette methode permet d'obtenir le créneaux d'un professeur
     */
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

    /**
     * Cette méthode permet de retourner le créneaux actuel
     * d'un professeur
     * 
     * @param string $cinProf
     * @return object|null
     */
    public function getCurrentCreneau(string $cinProf):?Creneaux {
        $query = $this->pdo->prepare('
            SELECT c.* FROM creneaux c WHERE c.cinProf = :cinProf 
            AND CURRENT_TIME BETWEEN c.heureDebut AND c.heureFin');

        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $result = $query->fetch();

        return $result;
    }

    

}