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

//username : CIN.nom



$pdo->exec("INSERT INTO utilisateur (username, nom, prenom, email, password, cin, role) VALUES 
        ('O00790130.haba', 'Haba', 'Marcel Fassou', 'marcelfassouhaba2003@gmail.com', '" . password_hash('O00790130', PASSWORD_BCRYPT) . "', 'O00790130', 'professeur'),
        ('BJ8478559.msaboue', 'Msaboue', 'Mohamed', 'mohamedmsb6@gmail.com', '" . password_hash('BJ8478559', PASSWORD_BCRYPT) . "', 'BJ8478559', 'etudiant'),
        ('CD8478559.claude', 'Claude', 'Douglas', 'tyu87885@gmail.com', '" . password_hash('CD8478559', PASSWORD_BCRYPT) . "', 'CD8478559', 'admin')");

$pdo->exec("INSERT INTO administrateur (nom, prenom, email, password, cin) VALUES 
        ('Claude', 'Douglas', 'tyu87885@gmail.com', '" . password_hash('CD8478559', PASSWORD_BCRYPT) . "', 'CD8478559')");

$pdo->exec("INSERT INTO professeur (nom, prenom, email, password, cin) VALUES 
        ('Haba', 'Marcel Fassou', 'marcelfassouhaba2003@gmail.com', '" . password_hash('O00790130', PASSWORD_BCRYPT) . "', 'O00790130')");

$pdo->exec("INSERT INTO etudiant (nom, prenom, cne, cin, email, password, idclasse) VALUES 
        ('Msaboue', 'Mohamed', 'BJ84785592', 'BJ8478559', 'mohamedmsb6@gmail.com', '" . password_hash('BJ8478559', PASSWORD_BCRYPT) . "', '1')");


$pdo->exec("INSERT INTO `filiere` (`idFiliere`, `nomFiliere`, `idDepartement`) VALUES
(1, 'Ingénierie Informatique et Technologies Emergentes', 1),
(2, 'Cybersécurité et Confiance Numérique', 1),
(3, 'Ingénierie des Systèmes d\'Information et de Communication', 1),
(4, 'Génie Civil', 2),
(5, 'Génie Electrique et Energétique', 2),
(6, 'Génie Industriel', 2),
(7, 'Années Préparatoires', 3)");

$pdo->exec("INSERT INTO `matiere` (`idMatiere`, `idProf`, `nomMatiere`, `idFiliere`) VALUES
    (1, 1, 'Théorie des langages et Compilation', 1),
    (2, 1, 'Informatique Théorique', 1),
    (3, 1, 'Developpement Web', 1)");

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

for ($i = 1; $i <= 100; $i++) {
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
        $stmt = $pdo->prepare("INSERT INTO Utilisateur (username, nom, prenom, email, password, cin, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $nom, $prenom, $email, $passwordHashed, $cin, $role]);

        // Récupération de l'ID inséré (clé étrangère pour Etudiant)
        $idUtilisateur = $pdo->lastInsertId();

        // 2. Insertion dans Etudiant
        $stmt = $pdo->prepare("INSERT INTO Etudiant (idEtudiant, nom, prenom, cne, cin, email, password, idClasse) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idUtilisateur, $nom, $prenom, $cne, $cin, $email, $passwordHashed, $idClasse]);

        echo "Utilisateur et étudiant $prenom $nom inséré avec succès !<br>";
    } catch (Exception $e) {
        echo "Erreur à l'insertion de $prenom $nom : " . $e->getMessage() . "<br>";
    }
}