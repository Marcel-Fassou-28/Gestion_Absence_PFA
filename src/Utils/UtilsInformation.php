<?php

namespace App\Utils;

use App\Abstract\Table;

use App\Model\Classe;
use App\Model\Creneaux;
use App\Model\Matiere;

use App\Model\Utils\CreneauComplet;
use App\Model\Utils\LastAbsence;
use App\Model\Utils\StatisticClass;

/**
 * Cette classe permet d'obtenir les informations nécessaires
 * concernant un professeur, comme :
 *    - La liste de ces créneaux
 *    - Les dernières statistiques
 *    - Les statistiques d'une classe
 */
class UtilsInformation extends Table {

    private $groupedByDay = [];
      
    /**
     * Cette méthode retourne la liste des créneaux
     * 
     * @param string $cinProf
     * @return array
     */
    public function getAllCreneaux(string $cinProf):?array {
        $query = $this->pdo->prepare('
            SELECT * FROM creneaux WHERE cinProf = :cinProf
        ');
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $creneaux = $query->fetchAll();
        
        $query = $this->pdo->prepare('
            SELECT * FROM matiere WHERE cinProf = :cinProf
        ');
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
        $matieres = $query->fetchAll();

        $query = $this->pdo->prepare('
            SELECT DISTINCT c.idClasse, c.nomClasse 
            FROM professeur p JOIN matiere m ON p.cinProf = m.cinProf JOIN 
            classe c ON m.idClasse = c.idClasse JOIN filiere f ON c.idFiliere = f.idFiliere
            JOIN niveau n ON c.idNiveau = n.idNiveau WHERE p.cinProf = :cinProf
        ');
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
        $classes = $query->fetchAll();
        
        $this->groupedByDay = $this->associerCreneaux($creneaux, $matieres, $classes);
        return $this->groupedByDay;
    }

    /**
     * Associe les créneaux avec leurs matières et classes, et les regroupe par jour.
     *
     * @param Creneaux[] $creneaux Tableau d'objets Creneaux
     * @param Matiere[] $matieres Tableau d'objets Matiere
     * @param Classe[] $classes Tableau d'objets Classe
     * @return array Tableau associatif avec jour comme clé et tableau de CreneauComplet comme valeur
     */
    function associerCreneaux($creneaux, $matieres, $classes): array {
        $matieresById = [];
        foreach ($matieres as $matiere) {
            $matieresById[$matiere->getIdMatiere()] = $matiere;
        }

        $classesById = [];
        foreach ($classes as $classe) {
            $classesById[$classe->getIdClasse()] = $classe;
        }

        // Créer les créneaux complets
        $creneauxComplets = [];
        foreach ($creneaux as $creneau) {
            $creneauComplet = new CreneauComplet();
            $creneauComplet->setJour($creneau->getJourSemaine());
            $creneauComplet->setHeureDebut($creneau->getHeureDebut());
            $creneauComplet->setHeureFin($creneau->getHeureFin());

            // Associer la matière (nom comme string)
            $idMatiere = $creneau->getIdMatiere();
            $matiere = $matieresById[$idMatiere] ?? null;
            $creneauComplet->setNomMatiere($matiere ? $matiere->getNomMatiere() : null);

            // Associer la classe (nom comme string)
            if ($matiere && method_exists($matiere, 'getIdClasse')) {
                $idClasse = $matiere->getIdClasse();
                $classe = $classesById[$idClasse] ?? null;
                $creneauComplet->setNomClasse($classe ? $classe->getNomClasse() : null);
            } else {
                $creneauComplet->setNomClasse(null);
            }

            $creneauxComplets[] = $creneauComplet;
        }

        // Regrouper par jour et trier par heure de début
        $groupedByDay = [];
        foreach ($creneauxComplets as $creneauComplet) {
            $jour = $creneauComplet->getJour();
            if (!isset($groupedByDay[$jour])) {
                $groupedByDay[$jour] = [];
            }
            $groupedByDay[$jour][] = $creneauComplet;
        }

        // Trier chaque jour par heureDebut
        foreach ($groupedByDay as $jour => &$creneauxDuJour) {
            usort($creneauxDuJour, function ($a, $b) {
                return strcmp($a->getHeureDebut(), $b->getHeureDebut());
            });
        }

        return $groupedByDay;
    }

    /**
     * Cette methode permet d'obtenir les informations sur la dernière absence
     * effectuer par un professeur
     * 
     * @param string $cinProf
     * @return LastAbsence|null
     */
    public function getInfoDerniereAbsence(string $cinProf):?LastAbsence {
        $query = $this->pdo->prepare('
            SELECT c.nomClasse, f.nomFiliere, m.nomMatiere, cr.heureDebut, cr.heureFin,
            a.date, COUNT(a.cinEtudiant) AS nombreAbsents FROM absence a JOIN 
            matiere m ON a.idMatiere = m.idMatiere JOIN classe c ON m.idClasse = c.idClasse
            JOIN filiere f ON c.idFiliere = f.idFiliere LEFT JOIN 
            creneaux cr ON cr.idMatiere = m.idMatiere AND cr.cinProf = m.cinProf
            WHERE m.cinProf = :cinProf AND a.date = (SELECT MAX(a2.date) FROM absence a2
                                                        JOIN matiere m2 ON a2.idMatiere = m2.idMatiere
                                                        WHERE m2.cinProf = :cinProf)
            GROUP BY c.nomClasse, f.nomFiliere, m.nomMatiere, cr.heureDebut, cr.heureFin, a.date LIMIT 1;
        ');
        $query->execute(['cinProf' => $cinProf]);
        $query->setFetchMode(\PDO::FETCH_CLASS, LastAbsence::class);

        $result = $query->fetch();

        return $result;
    }

    /**
     * Cette méthode permet de générer les effectifs des etudiants
     * (effectifs totaux et nombre total d'absents) par classe
     * Enseignée par un professeur et cela dans ces matières dispensées
     * 
     * @param string $cinProf
     * @return array
     */
    public function getInfoEffectifsNbrAbsents(string $cinProf):?array {
       $query = $this->pdo->prepare('
            SELECT c.idClasse, c.nomClasse, COUNT(DISTINCT e.cinEtudiant) AS effectifTotal, COUNT(DISTINCT a.cinEtudiant) AS totalAbsents
            FROM professeur p JOIN matiere m ON p.cinProf = m.cinProf JOIN classe c ON m.idClasse = c.idClasse
            JOIN etudiant e ON e.idClasse = c.idClasse LEFT JOIN absence a ON a.cinEtudiant = e.cinEtudiant AND a.idMatiere = m.idMatiere 
            WHERE  p.cinProf = :cinProf GROUP BY c.idClasse, c.nomClasse
       ');

       $query->execute(['cinProf' => $cinProf]);
       $query->setFetchMode(\PDO::FETCH_CLASS, StatisticClass::class);
       $result = $query->fetchAll();

        return $result;
    }
}