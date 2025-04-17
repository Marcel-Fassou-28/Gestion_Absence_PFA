<?php
namespace App;


use App\Model\Departement;
use App\Model\Filiere;
use App\Model\Classe;
use App\Model\Etudiant;
use App\Model\Professeur;
use App\Model\Absence;
use App\Model\Matiere;
use App\Model\Utilisateur;

use App\Model\Justificatif;
use App\Professeur\ProfessorTable;

class adminTable extends ProfessorTable
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
     * cette fonction d'avoir la liste de tous les elements
     * @param string $table
     * @return array
     */
    public function getAll(string $table, $class): array
    {
        $query = $this->pdo->prepare("SELECT * FROM $table");
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->$class);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }
    public function fieldsByDepartement(string $departement): array
    {
        $query = $this->pdo->prepare('
        SELECT DISTINCT f.nomFiliere FROM ' .
            $this->tableFiliere . ' f JOIN ' . $this->tableDepartement . ' d 
        ON d.idDepartement = f.idDepartement WHERE d.nomDepartement = :departement');
        $query->execute(['departement' => $departement]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classFiliere);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }
    public function classByFields(string $filiere): array
    {
        $query = $this->pdo->prepare('
        SELECT DISTINCT c.nomClasse FROM '
            . $this->tableClasse . ' c JOIN '
            . $this->tableFiliere . ' f ON 
        f.idFiliere = c.idFiliere  WHERE f.nomFiliere = :filiere');
        $query->execute(['filiere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classClasse);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }
    public function getprofByDepartement(string $depart): array
    {
        $query = $this->pdo->prepare('
    SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' .
            $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
    p ON m.cinProf = p.cinProf JOIN ' . $this->tableFiliere . '
    f ON m.idFiliere = f.idFiliere JOIN ' . $this->tableDepartement . '
    d ON f.idDepartement = d.idDepartement WHERE  
    d.nomDepartement = :departement');
        $query->execute(['departement' => $depart]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classProf);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    public function getProfByFiliere(string $filiere): array
    {
        $query = $this->pdo->prepare('
        SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' .
            $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
        p ON m.cinProf = p.cinProf JOIN ' . $this->tableFiliere . '
        f ON m.idFiliere = f.idFiliere WHERE  
        f.nomFiliere = :filiere');
        $query->execute(['filiere' => $filiere]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classProf);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    public function getProfByClass(string $class): array
    {
        $query = $this->pdo->prepare('
        SELECT DISTINCT p.idProf,p.cinProf,p.nom,p.prenom,p.email FROM ' .
            $this->tableMatiere . ' m JOIN ' . $this->tableProf . '
        p ON m.cinProf = p.cinProf JOIN ' . $this->tableClasse . '
        c ON m.idClasse = c.idClasse WHERE  
        c.nomClasse = :class');
        $query->execute(['class' => $class]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->classProf);
        $result = $query->fetchAll();
        return count($result) != 0 ? $result : [];
    }

    public function getAllJustificatif(): array
    {
        $query = $this->pdo->prepare('
    SELECT e.nom as nom,e.prenom as prenom, j.dateSoumission as 
    Date_Soumission FROM ' . $this->tableAbsence . ' a JOIN '
            . $this->tableJustificatif . ' j ON a.idAbsence = j.idAbsence'
            . ' JOIN ' . $this->tableEtudiant . ' e ON e.cinEtudiant = a.cinEtudiant');
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
        $sql1 = 'INSERT INTO ' . $this->tableProf .' (cinProf, nom, prenom, email) VALUES (?,?,?,?)';
        $sql2 = 'INSERT INTO ' . $this->tableUser . ' (username, cin, nom, prenom, email, password,role) VALUES (?,?,?,?,?,?,?)';
    try {
        {
            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare($sql1);
            $query->execute([$cin,$nom,$prenom,$email]);
            $query= $this->pdo->prepare($sql2);
            $query->execute([$username,$cin,$nom,$prenom,$email,$password,$role]);
            $this->pdo->commit();
            return true;
        }
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        echo $e->getMessage();
        return false;
    }
    }
    
}
?>