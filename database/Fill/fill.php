<?php

use App\Connection;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$pdo = Connection::getPDO();

/*
$pdo->exec('TRUNCATE TABLE utilisateur'); //Vide la table
$pdo->exec('TRUNCATE TABLE professeur');
$pdo->exec('TRUNCATE TABLE administrateur');
$pdo->exec('TRUNCATE TABLE etudiant');

//username : CIN.nom
*/

$pdo->exec("INSERT INTO utilisateur (username, nom, prenom, email, password, cin, role) VALUES 
        ('O00790130.haba', 'Haba', 'Marcel Fassou', 'marcelfassouhaba2003@gmail.com', '" . password_hash('O00790130', PASSWORD_BCRYPT) . "', 'O00790130', 'professeur'),
        ('BJ8478559.msaboue', 'Msaboue', 'Mohamed', 'mohamedmsb6@gmail.com', '" . password_hash('BJ8478559', PASSWORD_BCRYPT) . "', 'BJ8478559', 'etudiant'),
        ('CD8478559.claude', 'Claude', 'Douglas', 'tyu87885@gmail.com', '" . password_hash('CD8478559', PASSWORD_BCRYPT) . "', 'CD8478559', 'admin')");

$pdo->exec("INSERT INTO administrateur (nom, prenom, email, password, cin) VALUES 
        ('Claude', 'Douglas', 'tyu87885@gmail.com', '" . password_hash('CD8478559', PASSWORD_BCRYPT) . "', 'CD8478559')");

$pdo->exec("INSERT INTO professeur (nom, prenom, email, password, cin) VALUES 
        ('Haba', 'Marcel Fassou', 'marcelfassouhaba2003@gmail.com', '" . password_hash('O00790130', PASSWORD_BCRYPT) . "', 'O00790130')");

$pdo->exec("INSERT INTO etudiant (nom, prenom, cne, cin, email, password, idfiliere) VALUES 
        ('Msaboue', 'Mohamed', 'BJ84785592', 'BJ8478559', 'mohamedmsb6@gmail.com', '" . password_hash('BJ8478559', PASSWORD_BCRYPT) . "', '1')");
