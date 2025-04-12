<?php

use App\Connection;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$pdo = Connection::getPDO();


$pdo->exec('TRUNCATE TABLE utilisateur'); //Vide la table
$pdo->exec('TRUNCATE TABLE professeur');
$pdo->exec('TRUNCATE TABLE administrateur');
$pdo->exec('TRUNCATE TABLE etudiant');
$pdo->exec('TRUNCATE TABLE matiere');
$pdo->exec('TRUNCATE TABLE filiere');
$pdo->exec('TRUNCATE TABLE niveau');
$pdo->exec('TRUNCATE TABLE departement');
$pdo->exec('TRUNCATE TABLE classe');
$pdo->exec('TRUNCATE TABLE creneaux');

//username : CIN.nom


$pdo->exec("INSERT INTO utilisateur (username, nom, prenom, email, cin, password, role) VALUES 
        ('O00790130.haba', 'Haba', 'Marcel Fassou', 'marcelfassouhaba2003@gmail.com', 'O00790130','" . password_hash('O00790130', PASSWORD_BCRYPT)."', 'professeur'),
        ('JK84785592.kolie','kolie', 'Justin', 'justinkolie6@gmail.com', 'JK84785592','" . password_hash('JK84785592', PASSWORD_BCRYPT)."', 'professeur'),
        ('BJ8478559.msaboue', 'Msaboue', 'Mohamed', 'mohamedmsb6@gmail.com', 'BJ8478559','" . password_hash('BJ8478559', PASSWORD_BCRYPT)."' ,'etudiant'),
        ('CD8478559.claude', 'Claude', 'Douglas', 'tyu87885@gmail.com', 'CD8478559','" . password_hash('CD8478559', PASSWORD_BCRYPT)."',  'admin')");
        

$pdo->exec("INSERT INTO administrateur (nom, prenom, email, cinAdmin) VALUES 
        ('Claude', 'Douglas', 'tyu87885@gmail.com', 'CD8478559')");

$pdo->exec("INSERT INTO professeur (nom, prenom, email, cinProf) VALUES 
        ('Haba', 'Marcel Fassou', 'marcelfassouhaba2003@gmail.com', 'O00790130'),
        ('Kolie', 'Justin', 'justinkolie6@gmail.com', 'JK84785592')");


$pdo->exec("INSERT INTO etudiant (nom, prenom, cne, cinEtudiant, email, idclasse) VALUES 
        ('Msaboue', 'Mohamed', 'BJ84785592', 'BJ8478559', 'mohamedmsb6@gmail.com', 1)");


$pdo->exec("INSERT INTO filiere (idFiliere, nomFiliere, alias, idDepartement) VALUES
(1, 'Ingénierie Informatique et Technologies Emergentes','2ITE', 1),
(2, 'Cybersécurité et Confiance Numérique','CCN', 1),
(3, 'Ingénierie des Systèmes d\'Information et de Communication', 'ISIC', 1),
(4, 'Génie Civil','GC', 2),
(5, 'Génie Electrique et Energétique', 'GEE', 2),
(6, 'Génie Industriel', 'GI', 2),
(7, 'Années Préparatoires','AP', 3)");

$pdo->exec("INSERT INTO matiere (idMatiere, cinProf, nomMatiere, idFiliere, idClasse) VALUES
    (1, 'O00790130', 'Théorie des langages et Compilation', 1, 1),
    (2, 'O00790130', 'Informatique Théorique', 1, 1),
    (3, 'JK84785592', 'POO Java', 2, 7),
    (4, 'JK84785592', 'Informatique Théorique et Sécurité', 3, 4),
    (5, 'JK84785592', 'Cryptographie', 1, 4),
    (6, 'O00790130', 'Developpement Web et Java', 3, 5)");

$pdo->exec("INSERT INTO creneaux (heureDebut, heureFin, cinProf, idMatiere) VALUES
    ('08:30:00', '10:20:00', 'O00790130', 1),
    ('10:30:00', '12:20:00','O00790130', 2),
    ('18:00:00', '20:20:00','JK84785592', 3),
    ('13:30:00', '15:20:00', 'JK84785592', 4),
    ('08:30:00', '10:20:00','JK84785592', 5),
    ('00:00:00', '23:20:00','O00790130', 6)");

$pdo->exec("INSERT INTO `niveau` (`idNiveau`, `nomNiveau`) VALUES
(1, '1ere Année'),
(2, '2eme Année'),
(3, '3eme Année')");

$pdo->exec("INSERT INTO `departement` (`idDepartement`, `nomDepartement`) VALUES
(1, 'Télécommunications, Réseaux et Informatiques'),
(2, 'Sciences et Technologies Industrielles'),
(3, 'Années Préparatoires')");

$pdo->exec("INSERT INTO `classe` (`idClasse`, `nomClasse`, `idNiveau`, `idFiliere`) VALUES
(1, 'IITE-1', 1, 1),
(2, 'IITE-2', 2, 1),
(3, 'IITE-3', 3, 1),
(4, 'CCN-1', 1, 2),
(5, 'CCN-2', 2, 2),
(6, 'CCN-3', 3, 2),
(7, 'ISIC-1', 1, 3),
(8, 'ISIC-2', 2, 3),
(9, 'ISIC-3', 3, 3),
(10, 'GC-1', 1, 4),
(11, 'GC-2', 2, 4),
(12, 'GC-3', 3, 4),
(13, 'GEE-1', 1, 5),
(14, 'GEE-2', 2, 5),
(15, 'GEE-3', 3, 5),
(16, 'GI-1', 1, 6),
(17, 'GI-2', 2, 6),
(18, 'GI-3', 3, 6),
(19, 'AP-1', 1, 7),
(20, 'AP-2', 2, 7)");

$noms = ['Koné', 'Traoré', 'Diallo', 'Coulibaly', 'Kouadio', 'Ouattara', 'Zongo', 'Soro', 'Bamba', 'Yao'];
$prenoms = ['Fatou', 'Moussa', 'Jean', 'Mariama', 'Salif', 'Kevin', 'Aminata', 'Carine', 'Oumar', 'Estelle'];

// Fonction pour générer un CNE unique
function generateCNE($index) {
    return 'CNE' . str_pad($index, 6, '0', STR_PAD_LEFT);
}

// Fonction pour générer un CIN unique
function generateCIN($index) {
    return 'CI' . str_pad($index, 8, '0', STR_PAD_LEFT);
}

for ($i = 1; $i <= 105; $i++) {
    $nom = $noms[array_rand($noms)];
    $prenom = $prenoms[array_rand($prenoms)];
    $username = strtolower($nom . $i);
    $cne = generateCNE($i);
    $cin = generateCIN($i);
    $email = strtolower($nom . $prenom . $i . '@gmail.com');
    $passwordHashed = password_hash($cne, PASSWORD_BCRYPT);
    $idClasse = rand(1, 7); // uniquement entre 1 et 7
    $role = 'etudiant';

    try {
        // 1. Insertion dans Utilisateur
        $stmt = $pdo->prepare("INSERT INTO Utilisateur (username, cin, nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $cin, $nom, $prenom, $email, $passwordHashed, $role]);

        // Récupération de l'ID inséré (clé étrangère pour Etudiant)
        $idUtilisateur = $pdo->lastInsertId();

        // 2. Insertion dans Etudiant
        $stmt = $pdo->prepare("INSERT INTO Etudiant (idEtudiant, cinEtudiant, nom, prenom, cne, email, idClasse) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idUtilisateur, $cin, $nom, $prenom, $cne, $email, $idClasse]);

        echo "Utilisateur et étudiant $prenom $nom inséré avec succès !<br>";
    } catch (Exception $e) {
        echo "Erreur à l'insertion de $prenom $nom : " . $e->getMessage() . "<br>";
    }
}