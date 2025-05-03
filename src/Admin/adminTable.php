<?php
namespace App\Admin;


use App\Abstract\Table;
use App\Model\Departement;
use App\Model\Filiere;
use App\Model\Classe;
use App\Model\Etudiant;
use App\Model\Professeur;
use App\Model\Absence;
use App\Model\Creneaux;
use App\Model\Matiere;
use App\Model\Utilisateur;

use App\Model\Justificatif;
use App\Model\ListePresence;
use App\Model\Utils\Etudiant\UtilisateurEtudiant;
use App\Professeur\ProfessorTable;

class adminTable extends Table
{
    protected $tableDepartement = "departement";
    protected $tableFiliere = "filiere";
    protected $tableClasse = "classe";
    protected $tableProf = "professeur";
    protected $tableEtudiant = "etudiant";
    protected $tableMatiere = "matiere";
    protected $tableAbsence = "absence";

    protected $tableJustificatif = "justificatif";
    protected $tableUser = "utilisateur";

    protected $classDepartement = Departement::class;
    protected $classJustificatif = Justificatif::class;
    protected $classFiliere = Filiere::class;
    protected $classClasse = Classe::class;
    protected $classProf = Professeur::class;
    protected $classEtudiant = Etudiant::class;
    protected $classAbsence = Absence::class;
    protected $classMatiere = Matiere::class;
    protected $classUser = Utilisateur::class;

    /**
     * cette Méthode d'avoir la liste de tous les elements d'une table
     * En passant le nom de la table en paramètre
     *  
     * @param string $table
     * @param mixed $class
     * @return array
     */
    public function getAll(string $table, mixed $class, int $line = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM $table";
        if (in_array($table, ['etudiant', 'professeur', 'utilisateur'])) {
            $sql .= " ORDER BY nom ,prenom DESC";
        }
        if ($line !== 0) {
            $sql .= " LIMIT " . $line . " OFFSET " . $offset;
        }
        if ($table === "departement") {
            $sql .= " ORDER BY nomDepartement ASC";
        }

        $query = $this->pdo->prepare($sql);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->$class);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette méthode permet de récuperer les noms des filières à travers le département
     * 
     * @param string $departement
     * @return array
     */
    public function fieldsByDepartement(string $departement, int $line = 0, int $offset = 0): array
    {
        $query = '
            SELECT DISTINCT f.nomFiliere,f.alias, f.idFiliere FROM ' . $this->tableFiliere . ' f JOIN ' . $this->tableDepartement . ' d 
            ON d.idDepartement = f.idDepartement WHERE d.nomDepartement = :departement
            ORDER BY f.nomFiliere
        ';
        if ($line !== 0) {
            $query .= ' LIMIT ' . $line . ' OFFSET ' . $offset;
        }
        $sql = $this->pdo->prepare($query);
        $sql->execute(['departement' => $departement]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $sql->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    /**
     * cette methode permet de recuperer l'id d'un departement
     *  a partir de son nom
     * @param mixed $name
     * @return int|null
     */
    public function getIdDepartementByName(string $name): ?int
    {
        $sql = $this->pdo->prepare('
        SELECT idDepartement FROM departement 
        WHERE nomDepartement = :nomDepart
        ');
        $sql->execute(['nomDepart' => $name]);
        $sql->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $sql->fetch();
        return empty($result) ? null : $result['idDepartement'];

    }

    /**
     * cette methode permet de recuperer l'id d'un departement
     *  a partir de son nom
     * @param mixed $name
     * @return void
     */
    public function getIdFiliereByName(string $name): ?int
    {
        $sql = $this->pdo->prepare('
        SELECT idFiliere FROM Filiere 
        WHERE nomFiliere = :nomDepart
        ');
        $sql->execute(['nomDepart' => $name]);
        $sql->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $sql->fetch();
        return empty($result) ? null : $result['idFiliere'];

    }

    /**
     * Cette méthode permet de recuperer les classe à travers le nom de leur filière
     * 
     * @param string $filiere
     * @return array
     */
    public function classByFields(string $filiere): array
    {
        $query = $this->pdo->prepare('
            SELECT DISTINCT c.nomClasse FROM ' . $this->tableClasse . ' c JOIN ' . $this->tableFiliere . ' f ON 
            f.idFiliere = c.idFiliere  WHERE f.nomFiliere = :filiere
        ');
        $query->execute(['filiere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }
    /**
     * cette fonction permet de recuperer
     * la liste des matieres en fonction d'une classe precise
     * @param mixed $class
     * @return void
     */
    public function getMatiereByClass($class): array
    {
        $sql = 'SELECT m.nomMatiere FROM ' . $this->tableMatiere .
            ' m JOIN ' . $this->tableClasse . ' c ON 
        m.idClasse = c.idClasse WHERE c.nomClasse = :nom ';
        $query = $this->pdo->prepare($sql);

        $query->execute(['nom' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
        $result = $query->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    public function findClassByMatiere($matiere): ?string
    {
        $sql = '
        SELECT c.nomClasse as nom FROM matiere m JOIN classe c
         ON m.idClasse = c.idClasse WHERE m.nomMatiere = :nom
        ';

        $query = $this->pdo->prepare($sql);
        $query->execute(['nom' => $matiere]);
        $query->setFetchMode(\PDO::FETCH_ASSOC);
        return $query->fetch()['nom'];

    }

    /**
     * Cette méthode permet d'obtenir les informations de tous les professeurs qui
     * evolue dans un département
     * 
     * @param string $departement
     * @return array
     */
    public function getprofByDepartement(string $departement, int $line = 0, int $offset = 0): array
    {
        $sql = '
            SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' . $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
            p ON m.cinProf = p.cinProf JOIN ' . $this->tableFiliere . ' f ON m.idFiliere = f.idFiliere JOIN ' . $this->tableDepartement . '
            d ON f.idDepartement = d.idDepartement WHERE d.nomDepartement = :departement
        ';

        if ($line !== 0) {
            $sql .= ' LIMIT ' . $line . ' OFFSET ' . $offset;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['departement' => $departement]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classProf);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette méthode permet d'obtenir la liste des professeurs par
     * qui évolue dans une filière
     * 
     * @param string $filiere
     * @return array
     */
    public function getProfByFiliere(string $filiere, int $line = 0, int $offset = 0): array
    {
        $sql = '
            SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' . $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
            p ON m.cinProf = p.cinProf JOIN ' . $this->tableFiliere . ' f ON m.idFiliere = f.idFiliere WHERE  
            f.nomFiliere = :filiere
        ';
        if ($line !== 0) {
            $sql .= ' LIMIT ' . $line . ' OFFSET ' . $offset;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['filiere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classProf);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette méthode permet d'obtenir la liste des étudiants par filière
     * 
     * @param string $filiere
     * @return array
     */
    public function getStudentByFiliere(string $filiere, int $line = 0, int $offset = 0): array
    {
        $sql = "SELECT DISTINCT e.idEtudiant,e.cinEtudiant,e.cne,e.nom,e.prenom,e.email 
        FROM " . $this->tableClasse . " c JOIN " . $this->tableEtudiant .
            " e ON e.idClasse = c.idClasse JOIN " . $this->tableFiliere .
            " f ON f.idFiliere = c.idFiliere WHERE f.nomFiliere = :filiere ORDER BY e.nom ASC ";


        if ($line !== 0) {
            $sql .= " LIMIT " . $line . " OFFSET " . $offset;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['filiere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }


    /**
     * Cette méthode permet d'obtenir la liste des infos des profs par le nom
     * de leur classes
     * 
     * @param string $class
     * @param array
     */
    public function getProfByClass(string $class, int $line = 0, int $offset = 0): array
    {
        $sql = '
            SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' . $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
            p ON m.cinProf = p.cinProf JOIN ' . $this->tableClasse . ' c ON m.idClasse = c.idClasse WHERE c.nomClasse = :class
        ';
        if ($line !== 0) {
            $sql .= ' LIMIT ' . $line . ' OFFSET ' . $offset;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classProf);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette méthode permet d'obtenir la liste des étudiants par classe
     * 
     * @param string $class
     * @return array
     */
    public function getStudentByClass(string $class, int $line = 0, int $offset = 0): array
    {
        $sql = '
            SELECT DISTINCT e.idEtudiant,e.cinEtudiant,e.cne,e.nom,e.prenom,e.email FROM ' .
            $this->tableClasse . ' c JOIN ' . $this->tableEtudiant . ' e ON e.idClasse = c.idClasse WHERE 
            c.nomClasse = :class ORDER BY e.nom ASC 
        ';
        if ($line !== 0) {
            $sql .= ' LIMIT ' . $line . ' OFFSET ' . $offset;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }


    public function getAllJustificatif($line = 0, $offset = 0, $idMatiere = 0, $idClasse = 0): array
    {
        $sql = ' 
        SELECT e.nom as nom,e.prenom as prenom , e.cne as cne, 
        j.dateSoumission as Date_Soumission ,j.idJustificatif 
        as id FROM ' . $this->tableAbsence . ' a JOIN '
        . $this->tableJustificatif . ' j ON a.idAbsence = j.idAbsence' 
        . ' JOIN ' . $this->tableEtudiant . ' e ON 
        e.cinEtudiant = a.cinEtudiant WHERE j.statut = "en attente"';

        if ($idClasse != 0 || $idMatiere != 0) {
            $sql .= ' WHERE ';
            if ($idClasse != 0 && $idMatiere == 0) {
                $sql .= ' e.idClasse = ' . $idClasse;
            } else if ($idMatiere != 0 && $idClasse == 0) {
                $sql .= ' a.idMatiere = ' . $idMatiere;
            } else {
                $sql .= ' e.idClasse = ' . $idClasse .
                    ' AND a.idMatiere = ' . $idMatiere;
            }
        }

        $sql .= ' ORDER BY j.dateSoumission DESC';

        if ($line !== 0) {
            $sql .= ' LIMIT ' . $line . ' OFFSET ' . $offset;
        }

        $query = $this->pdo->prepare($sql);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $query->fetchALL();
        return count($result) != 0 ? $result : [];
    }

    public function getinfoJustificatifById($id)
    {
        $sql = $this->pdo->prepare("
        SELECT * FROM justificatif WHERE idJustificatif =:id;
        ");
        $sql->execute(['id' => $id]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classJustificatif);
        return $sql->fetch();
    }

    /**
     * trouver l'id de la classe a l'aide de son nom 
     * @param mixed $name
     * @return int
     */
    public function getIdClasseByClasseName($name): ?int
    {
        $sql = $this->pdo->prepare('
            SELECT idClasse FROM classe where nomClasse = :classe
            ');
        $sql->execute(['classe' => $name]);
        $sql->setFetchMode(\PDO::FETCH_ASSOC);
        return $sql->fetch()['idClasse'];
    }

    /**
     * trouver l'id de la matiere a l'aide de son nom
     * @param mixed $name
     * @return int
     */
    public function getIdMatiereByName($name): ?int
    {
        $sql = $this->pdo->prepare('
            SELECT idMatiere FROM matiere where nomMatiere = :matiere
            ');

        $sql->execute(['matiere' => $name]);
        $sql->setFetchMode(\PDO::FETCH_ASSOC);
        return $sql->fetch()['idMatiere'];
    }


    public function getProfByCin(string $cin): array
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->tableUser . ' WHERE cin = :cin ');
        $query->execute(['cin' => $cin]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classUser);
        $result = $query->fetchALL();
        return count($result) ? $result : [];
    }

    /**
     * Permet de recuperer les informations d'un professeur à travers son cin
     * 
     * @param string $cin
     * @return Utilisateur
     */
    public function getProfesseurByCIN(string $cin): ?Utilisateur
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->tableUser . ' WHERE cin = :cin ');
        $query->execute(['cin' => $cin]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classUser);
        $result = $query->fetch();

        return $result ? $result : null;
    }


    public function getStudentByCin(string $cin): array
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->tableEtudiant . ' WHERE cinEtudiant = :cin ');
        $query->execute(['cin' => $cin]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchALL();
        return count($result) ? $result : [];
    }

    /**
     * Cette methode permet de retourner un etudiant et sa classe
     * 
     * @param string $cin
     * @return UtilisateurEtudiant
     */
    public function getStudentInfoByCIN(string $cin): ?UtilisateurEtudiant
    {
        $query = $this->pdo->prepare(
            'SELECT u.*, e.cne, c.nomClasse FROM utilisateur u JOIN ' . $this->tableEtudiant . ' e ON u.cin = e.cinEtudiant
            JOIN classe c ON c.idClasse = e.idClasse WHERE cinEtudiant = :cin '
        );
        $query->execute(['cin' => $cin]);
        $query->setFetchMode(\PDO::FETCH_CLASS, UtilisateurEtudiant::class);
        $result = $query->fetch();

        return $result ? $result : null;
    }

    // les prochaines fonction sont destinees a la gestion des prof et des etudiants
    public function ModifierProf($username, $newcin, $nom, $prenom, $email, $oldCin): bool
    {
        $param = [
            'username' => $username,
            'newcin' => $newcin,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'oldcin' => $oldCin
        ];
        try {
            $this->pdo->beginTransaction();
            $sql1 = 'UPDATE ' . $this->tableProf . ' SET cinProf = :newcin,
                nom = :nom, prenom = :prenom, email = :email WHERE cinProf = :oldcin';
            $sql2 = 'UPDATE ' . $this->tableUser . ' SET username = :username, 
                cin = :newcin, nom = :nom, prenom = :prenom, email = :email WHERE cin = :oldcin';
            $sql3 = 'UPDATE matiere SET cinProf = :newcin WHERE cinProf = :oldcin';
            $query = $this->pdo->prepare($sql1);
            $query->execute([
                'newcin' => $newcin,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'oldcin' => $oldCin
            ]);
            $query = $this->pdo->prepare($sql2);
            $query->execute($param);

            $query2 = $this->pdo->prepare($sql3);
            $query2->execute(['newcin' => $newcin, 'oldcin' => $oldCin]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function SuprimerProf($cin): bool
    {
        try {
            $this->pdo->beginTransaction();
            $sql1 = 'DELETE FROM ' . $this->tableProf . ' WHERE cinProf = :cin';
            $sql2 = 'DELETE FROM ' . $this->tableUser . ' WHERE cin = :cin';
            $query = $this->pdo->prepare($sql1);
            $query->execute(['cin' => $cin]);
            $query = $this->pdo->prepare($sql2);
            $query->execute(['cin' => $cin]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function AddProfUser($cin, $nom, $prenom, $email, $username, $password, $role): bool
    {
        $sql1 = 'INSERT INTO ' . $this->tableProf . ' (cinProf, nom, prenom, email) VALUES (?,?,?,?)';
        $sql2 = 'INSERT INTO ' . $this->tableUser . ' (username, cin, nom, prenom, email, password,role, nomPhoto) VALUES (?,?,?,?,?,?,?, ?)';
        try { {
                $this->pdo->beginTransaction();
                $query = $this->pdo->prepare($sql1);
                $query->execute([$cin, $nom, $prenom, $email]);
                $query = $this->pdo->prepare($sql2);
                $query->execute([$username, $cin, $nom, $prenom, $email, $password, $role, 'avatar.png']);
                $this->pdo->commit();
                return true;
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function AddStudentUser($cin, $nom, $prenom, $email, $username, $password, $cne, $idClasse, $role): bool
    {
        $sql1 = 'INSERT INTO ' . $this->tableEtudiant . ' (cinEtudiant, nom, prenom,cne, email,idClasse) VALUES (?,?,?,?,?,?)';
        $sql2 = 'INSERT INTO ' . $this->tableUser . ' (username, cin, nom, prenom, email, password,role) VALUES (?,?,?,?,?,?,?)';
        try { {
                $this->pdo->beginTransaction();
                $query = $this->pdo->prepare($sql1);
                $query->execute([$cin, $nom, $prenom, $cne, $email, $idClasse]);
                $query = $this->pdo->prepare($sql2);
                $query->execute([$username, $cin, $nom, $prenom, $email, $password, $role]);
                $this->pdo->commit();
                return true;
            }
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function ModifierStudent($newcin, $nom, $prenom, $email, $username, $cne, $idClasse, $oldCin): bool
    {
        $param = [
            'username' => $username,
            'newcin' => $newcin,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'oldcin' => $oldCin
        ];
        try {
            $this->pdo->beginTransaction();
            $sql1 = 'UPDATE ' . $this->tableEtudiant . ' SET cinEtudiant = :newcin,
                nom = :nom, prenom = :prenom, email = :email,cne = :cne, idClasse = :idClasse WHERE cinEtudiant = :oldcin';
            $sql2 = 'UPDATE ' . $this->tableUser . ' SET username = :username, 
                cin = :newcin, nom = :nom, prenom = :prenom, email = :email WHERE cin = :oldcin';

            $sql3 = 'UPDATE ' . $this->tableAbsence . ' 
            SET cinEtudiant = :newcin WHERE cinEtudiant = :oldcin';
            $query = $this->pdo->prepare($sql2);
            $query->execute($param);

            $query = $this->pdo->prepare($sql3);
            $query->execute([
                'oldcin' => $oldCin,
                'newcin' => $newcin
            ]);



            $query = $this->pdo->prepare($sql1);
            $query->execute([
                'newcin' => $newcin,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'cne' => $cne,
                'idClasse' => $idClasse,
                'oldcin' => $oldCin
            ]);




            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
            return false;
        }
    }


    public function SuprimerStudent($cin): bool
    {
        try {
            $this->pdo->beginTransaction();
            $sql1 = 'DELETE FROM ' . $this->tableEtudiant . ' WHERE cinEtudiant = :cin';
            $sql2 = 'DELETE FROM ' . $this->tableUser . ' WHERE cin = :cin';
            $sql3 = 'DELETE FROM ' . $this->tableAbsence . ' WHERE cinEtudiant = :cin';
            $query = $this->pdo->prepare($sql3);
            $query->execute(['cin' => $cin]);
            $query = $this->pdo->prepare($sql2);
            $query->execute(['cin' => $cin]);
            $query = $this->pdo->prepare($sql1);
            $query->execute(['cin' => $cin]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    /**
     * cette methode permet d'ajouter une filiere 
     * et de creer le nombre de creer le nombre de classe qui permetra de completer 
     * les annes d'etude de la filiere
     * @param string $nom nom de la fliere
     * @param string $alias alias de la filiere
     * @param int $departement departement auquel sera associer la filiere
     * @return bool
     */
    public function addFiliere(int $idFiliere, string $nom, string $alias, int $departement, $AnneeEtude): bool
    {
        try {
            $this->pdo->beginTransaction();
            $sql = "
            INSERT INTO filiere (nomFiliere, alias,idDepartement)
            Values(?,?,?)";
            $query = $this->pdo->prepare($sql);
            $query->execute([$nom, $alias, $departement]);

            for ($i = 1; $i <= $AnneeEtude; $i++):
                $sql = "
                INSERT INTO classe (nomClasse, idNiveau,idFiliere)
                VALUES (?,?,?)";
                $query = $this->pdo->prepare($sql);
                $query->execute([$alias . "-" . $i, $i, $idFiliere]);
            endfor;

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function SuprimerFiliere($idFiliere): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql2 = 'DELETE FROM ' . $this->tableClasse . ' WHERE idFiliere = :id';
            $sql1 = 'DELETE FROM ' . $this->tableMatiere . ' WHERE idFiliere = :id';
            $sql3 = 'DELETE FROM ' . $this->tableFiliere . ' WHERE idFiliere = :id';
            $query1 = $this->pdo->prepare($sql1);
            $query1->execute(['id' => $idFiliere]);
            $query2 = $this->pdo->prepare($sql2);
            $query2->execute(['id' => $idFiliere]);
            $query = $this->pdo->prepare($sql3);
            $query->execute(['id' => $idFiliere]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }
    public function modifierFiliere(int $idFiliere, string $nomfiliere, $oldnomFiliere, string $alias, int $departement): bool
    {
        $param = [
            'id' => $idFiliere,
            'nomfiliere' => $nomfiliere,
            'alias' => $alias,
            'departement' => $departement
        ];
        try {
            $nbreClasse = count($this->classByFields($oldnomFiliere));
            $this->pdo->beginTransaction();

            for ($i = 1; $i <= $nbreClasse; $i++):
                $sql2 = 'UPDATE ' . $this->tableClasse . ' SET nomClasse = :nomClasse  Where idFiliere = :id';
                $query = $this->pdo->prepare($sql2);
                $query->execute([
                    'nomClasse' => $alias . "-" . $i,
                    'id' => $idFiliere
                ]);
            endfor;

            $sql3 = 'UPDATE ' . $this->tableFiliere . ' SET nomFiliere = :nomfiliere,
             alias = :alias, idDepartement = :departement Where idFiliere = :id';

            $query = $this->pdo->prepare($sql3);
            $query->execute($param);





            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
            return false;
        }
    }


    /**
     * retourne l'id de la matiere et la date a laquelle les absences en cette matiere ont ete soumis
     * @return array
     */
    public function getAbsenceMatiere(): array
    {
        $sql = $this->pdo->prepare('SELECT DISTINCT idMatiere,date FROM ' . $this->tableAbsence .
            ' ORDER BY date DESC LIMIT 10');
        $sql->execute();
        $sql->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];

    }

    /**
     * trouver le nom de la matiere par son id 
     * @param mixed $id
     * @return object
     */
    public function getMatiereById($id): ?Matiere
    {
        $sql = $this->pdo->prepare('SELECT nomMatiere,idClasse FROM ' . $this->tableMatiere . ' WHERE idMatiere = :id ');
        $sql->execute(['id' => $id]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
        $result = $sql->fetch();
        return  (!empty($result)) ? $result : null;
    }


    /**
     * trouver les infos de la filiere par son id 
     * @param mixed $id
     * @return array
     */
    public function getFieldsById($id): array
    {
        $sql = $this->pdo->prepare('SELECT * FROM ' . $this->tableFiliere . ' WHERE idFiliere = :id ');
        $sql->execute(['id' => $id]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }
    
    public function getDepartementById($id): array
    {
        $sql = $this->pdo->prepare('SELECT * FROM ' . $this->tableDepartement . ' WHERE idDepartement = :id ');
        $sql->execute(['id' => $id]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classDepartement);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    public function getClassById($id): array
    {
        $sql = $this->pdo->prepare('SELECT nomClasse FROM ' . $this->tableClasse . ' WHERE idClasse = :id ');
        $sql->execute(['id' => $id]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    public function getAbsenceAllStudentByMatiere($idMatiere, $date): array
    {
        $querry = 'SELECT e.nom,e.prenom  FROM ' . $this->tableEtudiant . ' e JOIN ' . $this->tableAbsence .
            ' a ON a.cinEtudiant = e.cinEtudiant WHERE a.idMatiere= :idMatiere AND a.date = :date ORDER BY nom,prenom ASC';
        $sql = $this->pdo->prepare($querry);
        $sql->execute([
            'idMatiere' => $idMatiere,
            'date' => $date
        ]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }


    public function getAllMatiereByclass($class): array
    {
        $querry = "SELECT * FROM matiere WHERE idClasse = :id ORDER BY nomMatiere";
        $sql = $this->pdo->prepare($querry);
        $sql->execute(['id' => $class]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    /**
     * retourne le nombre d'absence  en heure d'un etudiant dans une matiere
     * @param mixed $cin
     * @param mixed $matiere
     * @return int
     */
    public function getAbsenceStudentByMatiere($cin, $matiere)
    {
        $querry = "
        SELECT COUNT(e.cinEtudiant) as nbreAbsences FROM absence a JOIN 
        etudiant e ON  e.cinEtudiant = a.cinEtudiant JOIN matiere m
        ON m.idMatiere = a.idMatiere WHERE a.cinEtudiant =:cin AND 
        a.idMatiere = :id AND a.idAbsence NOT IN 
        (SELECT idAbsence FROM justificatif WHERE statut = 'accepté')";

        $sql = $this->pdo->prepare($querry);
        $sql->execute([
            'cin' => $cin,
            'id' => $matiere
        ]);
        $result = $sql->fetch();
        return $result['nbreAbsences'] * 2;
    }

    /**
     * cette fonction permet d'avoir la liste des etudiant 
     * privees de passer l'exam dans une matiere
     * @param mixed $matiere
     * @return array
     */
    public function getPrivateStudentToPastExamByMatiere($matiere, int $line = 0, int $offset = 0): array
    {
        "SELECT idAbsence FROM justificatif WHERE statut = 'accepté'";
        $querry = "
        SELECT e.nom as nom,e.prenom as prenom,e.cinEtudiant as cinEtudiant,
         e.cne as cne, e.email as email FROM absence a JOIN 
        etudiant e ON  e.cinEtudiant = a.cinEtudiant JOIN matiere m
        ON m.idMatiere = a.idMatiere WHERE a.idMatiere = :id AND a.idAbsence NOT IN 
        (SELECT idAbsence FROM justificatif WHERE statut = 'accepté')
        GROUP BY e.nom, e.prenom, e.cinEtudiant HAVING COUNT(a.cinEtudiant)>=4";

        if ($line !== 0) {
            $querry .= " LIMIT " . $line . " OFFSET " . $offset;
        }
        $sql = $this->pdo->prepare($querry);
        $sql->execute([
            'id' => $matiere
        ]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    /**
     * cette methoodde permet de justifier une ou plusieurs absence d'un etudiant
     *  entre deux date donnee
     * @param int $id l'identifiant du justificatif pour mettre le satatut a acepte
     * @param string $cinEtudiant cin de l'etudiant pour supprimer les absence de l'etudiant
     * @param string $date1 date de debut de validite du justificatif
     * @param string $date2 date de fin de validite du justificatif
     * @return bool
     */
    public function justifierAbscence(int $id, string $cinEtudiant, string $date1, string $date2): bool
    {

        try {
            $this->pdo->beginTransaction();
            $query = "UPDATE justificatif SET statut = 'accepté' WHERE idJustificatif
             = :id AND idAbsence 
            IN (SELECT idAbsence FROM absence WHERE cinEtudiant = :cinEtudiant
             AND DATE(date) BETWEEN :date1 AND :date2)";

            $querrry = "DELETE FROM absence WHERE cinEtudiant = :cinEtudiant  AND DATE(date) BETWEEN :date1 AND :date2";
            $sql = $this->pdo->prepare($query);
            $sql->execute([
                'id' => $id,
                'cinEtudiant' => $cinEtudiant,
                'date1' => $date1,
                'date2' => $date2
            ]);

            $sql = $this->pdo->prepare($querrry);
            $sql->execute([
                'cinEtudiant' => $cinEtudiant,
                'date1' => $date1,
                'date2' => $date2
            ]);
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }

    }

    /**
     * Cette méthode est défini pour recuperer la liste de tous les fichiers d'absence
     * soumis dans les classes
     * 
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getAllFichierListPresence(int $line = 0, int $offset = 0): array
    {
        $query = $this->pdo->prepare('
            SELECT lp.*, CONCAT(p.nom ," ", p.prenom) as nomPrenom FROM listepresence lp JOIN professeur p ON lp.cinProf = p.cinProf ORDER BY lp.date DESC
            LIMIT ' . $line . ' OFFSET ' . $offset .' 
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, ListePresence::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }

    /**
     * Cette méthode est défini pour recuperer la liste de tous les fichiers d'absence
     * soumis dans les classes par classe
     * 
     * @param int $line
     * @param int $offset
     * @param string $classe
     * @return array
     */
    public function getAllFichierListPresenceByClasse(string $classe, int $line = 0, int $offset = 0): array
    {
        $query = $this->pdo->prepare('
            SELECT lp.*, CONCAT(p.nom ," ", p.prenom) as nomPrenom FROM listepresence lp JOIN professeur p ON lp.cinProf = p.cinProf WHERE
            lp.classe = :classe ORDER BY lp.date DESC LIMIT ' . $line . ' OFFSET ' . $offset .'
        ');
        $query->execute(['classe' => $classe]);
        $query->setFetchMode(\PDO::FETCH_CLASS, ListePresence::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }

    /**
     * Cette méthode est défini pour recuperer la liste de tous les fichiers d'absence
     * soumis dans les classes by classe et par matiere
     * 
     * @param string $classe
     * @param string $matiere
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getAllFichierListPresenceByClasseMatiere(string $classe, string $matiere, int $line = 0, int $offset = 0): array
    {
        $query = $this->pdo->prepare('
            SELECT lp.*, CONCAT(p.nom ," ", p.prenom) as nomPrenom FROM listepresence lp JOIN professeur p ON lp.cinProf = p.cinProf WHERE
            lp.classe = :classe AND lp.matiere = :matiere ORDER BY lp.date DESC LIMIT ' . $line . ' OFFSET ' . $offset . '
        ');
        $query->execute(['classe' => $classe, 'matiere' => $matiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, ListePresence::class);
        $result = $query->fetchAll();

        return $result ?? [];
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
     * Cette méthode permet d'afficher tous les créneaux
     * 
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getAllCreneaux(int $line, int $offset): ?array
    {
        $query = $this->pdo->prepare('
            SELECT C.jourSemaine, C.heureDebut, C.heureFin, C.id, P.nom as nomProf, P.prenom as prenomProf, Cl.nomClasse, M.nomMatiere
            FROM Creneaux C JOIN Professeur P ON C.cinProf = P.cinProf JOIN Matiere M ON C.idMatiere = M.idMatiere
            JOIN Classe Cl ON M.idClasse = Cl.idClasse ORDER BY 
            FIELD(C.jourSemaine, \'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\'), C.heureDebut LIMIT ' . $line . ' OFFSET ' .$offset . '
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }


    /**
     * Cette méthode permet d'afficher tous les créneaux
     * par filière 
     * 
     * @param string $filiere
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getAllCreneauxByFilieres(string $filiere, int $line, int $offset): ?array
    {
        $query = $this->pdo->prepare('
            SELECT C.jourSemaine, C.heureDebut, C.heureFin, C.id, P.nom as nomProf, P.prenom as prenomProf, Cl.nomClasse, M.nomMatiere
            FROM Creneaux C JOIN Professeur P ON C.cinProf = P.cinProf JOIN Matiere M ON C.idMatiere = M.idMatiere
            JOIN Classe Cl ON M.idClasse = Cl.idClasse JOIN Filiere f ON Cl.idFiliere = f.idFiliere WHERE f.nomFiliere = :nomFiliere  ORDER BY 
            FIELD(C.jourSemaine, \'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\'), C.heureDebut LIMIT ' . $line . ' OFFSET ' .$offset . '
        ');
        $query->execute(['nomFiliere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }


    /**
     * Cette méthode permet d'afficher tous les créneaux
     * par filière et par classes
     * 
     * @param string $filiere
     * @param string $classe
     * @param int $line
     * @param int $offset
     * @return array
     */
    public function getAllCreneauxByFilieresClasses(string $filiere, string $classe , int $line, int $offset): ?array
    {
        $query = $this->pdo->prepare('
            SELECT C.jourSemaine, C.heureDebut, C.heureFin, C.id, P.nom as nomProf, P.prenom as prenomProf, Cl.nomClasse, M.nomMatiere
            FROM Creneaux C JOIN Professeur P ON C.cinProf = P.cinProf JOIN Matiere M ON C.idMatiere = M.idMatiere
            JOIN Classe Cl ON M.idClasse = Cl.idClasse JOIN Filiere f ON Cl.idFiliere = f.idFiliere WHERE f.nomFiliere = :nomFiliere AND Cl.nomClasse = :nomClasse ORDER BY 
            FIELD(C.jourSemaine, \'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\'), C.heureDebut LIMIT ' . $line . ' OFFSET ' .$offset . '
        ');
        $query->execute([
            'nomClasse' => $classe,
            'nomFiliere' => $filiere
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }

}
?>