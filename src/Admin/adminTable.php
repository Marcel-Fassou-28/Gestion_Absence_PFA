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
    public function getAll(string $table,mixed $class,int $line = 0,int $offset = 0): array
    {
        $sql = "SELECT * FROM $table";
        if ( in_array($table,['etudiant','rofesseur','utilisateur'])){
            $sql .= " ORDER BY nom ,prenom DESC";
        }
        if ($line !== 0){
            $sql .= " LIMIT ". $line . " OFFSET ".$offset;
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
    public function fieldsByDepartement(string $departement): array
    {
        $query = $this->pdo->prepare('
            SELECT DISTINCT f.nomFiliere FROM ' . $this->tableFiliere . ' f JOIN ' . $this->tableDepartement . ' d 
            ON d.idDepartement = f.idDepartement WHERE d.nomDepartement = :departement
        ');
        $query->execute(['departement' => $departement]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
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
            SELECT DISTINCT c.nomClasse FROM '. $this->tableClasse . ' c JOIN '. $this->tableFiliere . ' f ON 
            f.idFiliere = c.idFiliere  WHERE f.nomFiliere = :filiere
        ');
        $query->execute(['filiere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    /**
     * Cette méthode permet d'obtenir les informations de tous les professeurs qui
     * evolue dans un département
     * 
     * @param string $departement
     * @return array
     */
    public function getprofByDepartement(string $departement,int $line = 0,int $offset = 0): array
    {
        $sql ='
            SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' . $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
            p ON m.cinProf = p.cinProf JOIN ' . $this->tableFiliere . ' f ON m.idFiliere = f.idFiliere JOIN ' . $this->tableDepartement . '
            d ON f.idDepartement = d.idDepartement WHERE d.nomDepartement = :departement
        ';
        
        if ($line !== 0){
            $sql .= ' LIMIT '.$line . ' OFFSET ' . $offset;
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
    public function getProfByFiliere(string $filiere,int $line = 0,int $offset = 0): array
    {
        $sql ='
            SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' .$this->tableMatiere . ' m JOIN ' . $this->tableProf . '
            p ON m.cinProf = p.cinProf JOIN ' . $this->tableFiliere . ' f ON m.idFiliere = f.idFiliere WHERE  
            f.nomFiliere = :filiere
        ';
        if ($line !== 0){
            $sql .= ' LIMIT '.$line . ' OFFSET ' . $offset;
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
    public function getStudentByFiliere(string $filiere,int $line = 0,int $offset = 0): array
    {
        $sql = "SELECT DISTINCT e.idEtudiant,e.cinEtudiant,e.cne,e.nom,e.prenom,e.email 
        FROM " . $this->tableClasse . " c JOIN " . $this->tableEtudiant . 
        " e ON e.idClasse = c.idClasse JOIN " . $this->tableFiliere .
        " f ON f.idFiliere = c.idFiliere WHERE f.nomFiliere = :filiere ORDER BY e.nom ASC ";
        

        if ($line !== 0){
            $sql .= " LIMIT ".$line . " OFFSET ". $offset;
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
    public function getProfByClass(string $class,int $line = 0,int $offset = 0): array
    {
        $sql = '
            SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' . $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
            p ON m.cinProf = p.cinProf JOIN ' . $this->tableClasse . ' c ON m.idClasse = c.idClasse WHERE c.nomClasse = :class
        ';
        if ($line !== 0){
            $sql .= ' LIMIT '.$line . ' OFFSET ' . $offset;
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
    public function getStudentByClass(string $class,int $line = 0,int $offset = 0): array
    {
        $sql = '
            SELECT DISTINCT e.idEtudiant,e.cinEtudiant,e.cne,e.nom,e.prenom,e.email FROM ' .
            $this->tableClasse . ' c JOIN ' . $this->tableEtudiant . ' e ON e.idClasse = c.idClasse WHERE 
            c.nomClasse = :class ORDER BY e.nom ASC 
        ';
        if ($line !== 0){
            $sql .= ' LIMIT '.$line . ' OFFSET ' . $offset;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classEtudiant);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }


    public function getAllJustificatif(): array
    {
        $query = $this->pdo->prepare('
            SELECT e.nom as nom,e.prenom as prenom, j.dateSoumission as Date_Soumission FROM ' . $this->tableAbsence . ' a JOIN '
            . $this->tableJustificatif . ' j ON a.idAbsence = j.idAbsence' . ' JOIN ' . $this->tableEtudiant . ' e ON e.cinEtudiant = a.cinEtudiant
            ORDER BY e.nom,e.prenom DESC
        ');
        $query->execute();
        $result = $query->fetchALL();
        return count($result) != 0 ? $result : [];
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
    public function getProfesseurByCIN(string $cin):?Utilisateur
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
    public function getStudentInfoByCIN(string $cin):?UtilisateurEtudiant
    {
        $query = $this->pdo->prepare(
            'SELECT u.*, e.cne, c.nomClasse FROM utilisateur u JOIN '. $this->tableEtudiant . ' e ON u.cin = e.cinEtudiant
            JOIN classe c ON c.idClasse = e.idClasse WHERE cinEtudiant = :cin ');
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
            $query = $this->pdo->prepare($sql2);
            $query->execute($param);
            echo "etudiant modifier";


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


    public function getAbsenceMatiere(): array
    {
        $sql = $this->pdo->prepare('SELECT DISTINCT idMatiere,date FROM ' . $this->tableAbsence .
            ' ORDER BY date DESC LIMIT 10');
        $sql->execute();
        $sql->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];

    }

    public function getMatiereById($id): array
    {
        $sql = $this->pdo->prepare('SELECT nomMatiere,idClasse FROM ' . $this->tableMatiere . ' WHERE idMatiere = :id ');
        $sql->execute(['id' => $id]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
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

    
    public function getAllMatiereByclass($class):array{
        $querry = "SELECT * FROM matiere WHERE idClasse = :id ORDER BY nomMatiere";
        $sql=$this->pdo->prepare($querry);
        $sql->execute(['id' => $class]);
        $sql->setFetchMode(\PDO::FETCH_CLASS, $this->classMatiere);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    public function getAbsenceStudentByMatiere($cin,$matiere){
        $querry = "SELECT COUNT(a.cinEtudiant) as nbreAbsences FROM absence a JOIN 
        etudiant e ON  e.cinEtudiant = a.cinEtudiant JOIN matiere m
        ON m.idMatiere = a.idMatiere WHERE a.cinEtudiant =:cin AND 
        a.idMatiere = :id";
        
        $sql=$this->pdo->prepare($querry);
        $sql->execute([
            'cin' => $cin,
            'id' => $matiere]);
        $result = $sql->fetch();
        return $result['nbreAbsences'] * 2;
    }

    public function getPrivateStudentToPastExamByMatiere($matiere):array{
        $querry = "SELECT e.nom as nom,e.prenom as prenom,e.cinEtudiant as cinEtudiant FROM absence a JOIN 
        etudiant e ON  e.cinEtudiant = a.cinEtudiant JOIN matiere m
        ON m.idMatiere = a.idMatiere WHERE a.idMatiere = :id GROUP BY e.nom, e.prenom, e.cinEtudiant
         HAVING COUNT(a.cinEtudiant)>=4";
        
        $sql=$this->pdo->prepare($querry);
        $sql->execute([
            'id' => $matiere]);
        $sql->setFetchMode(\PDO::FETCH_CLASS,$this->classEtudiant);
        $result = $sql->fetchALL();
        return count($result) > 0 ? $result : [];
    }

    /**
     * Cette méthode est défini pour recuperer la liste de tous les fichiers d'absence
     * soumis dans les classes
     * 
     * @return array
     */
    public function getAllFichierListPresence():array {
        $query = $this->pdo->prepare('
            SELECT lp.*, CONCAT(p.nom ," ", p.prenom) as nomPrenom FROM listepresence lp JOIN professeur p ON lp.cinProf = p.cinProf ORDER BY date DESC
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, ListePresence::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }
    
    /*
     * cette fonction permet generer une url avec comme un formulaire soumit avec get
     * @param mixed $col
     * @param mixed $val
     * @return string
     */
    public  function test($col,$val):string{
        return http_build_query(array_merge($_GET,[$col => $val]));
    }

    /**
     * Cette méthode permet d'afficher tous les créneaux
     * 
     * @return array
     */
    public function getAllCreneaux():?array {
        $query = $this->pdo->prepare('
            SELECT C.jourSemaine, C.heureDebut, C.heureFin, C.id, P.nom as nomProf, P.prenom as prenomProf, Cl.nomClasse, M.nomMatiere
            FROM Creneaux C JOIN Professeur P ON C.cinProf = P.cinProf JOIN Matiere M ON C.idMatiere = M.idMatiere
            JOIN Classe Cl ON M.idClasse = Cl.idClasse ORDER BY 
            FIELD(C.jourSemaine, \'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\'), C.heureDebut;
        ');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
        $result = $query->fetchAll();

        return $result ?? [];
    }

}
?>