<?php

use App\Connection;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$pdo = Connection::getPDO();

// Nouveau professeur : Adama Keita
$cinProf = 'CI99999999';
$nomProf = 'Keita';
$prenomProf = 'Adama';
$usernameProf = strtolower($cinProf . '.' . $nomProf);
$emailProf = strtolower($prenomProf . $nomProf . '@gmail.com');
$passwordProf = password_hash($cinProf, PASSWORD_BCRYPT);

try {
    // Insertion dans Utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateur (username, cin, nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$usernameProf, $cinProf, $nomProf, $prenomProf, $emailProf, $passwordProf, 'professeur']);

    // Insertion dans Professeur
    $stmt = $pdo->prepare("INSERT INTO professeur (cinProf, nom, prenom, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$cinProf, $nomProf, $prenomProf, $emailProf]);

    echo "Professeur $prenomProf $nomProf ajouté avec succès !<br>";
} catch (Exception $e) {
    echo "Erreur à l'insertion du professeur : " . $e->getMessage() . "<br>";
}
