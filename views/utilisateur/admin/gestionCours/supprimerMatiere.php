<?php

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}


use App\Connection;
$pdo = Connection::getPDO();
$success = null;

$matiere = $_GET['id_matiere'];
if (isset($matiere)) {
    $query = $pdo->prepare('DELETE FROM matiere WHERE idMatiere = :idMatiere');
    $query->execute(['idMatiere' => $matiere]);
    $success = 1;
    header('location:' . $router->url('liste-matiere-admin') .'?matiere=1&p=0&success_delete='.$success);
    exit();
} else {
    $success = 0;
    header('location:' . $router->url('liste-matiere-admin') .'?matiere=1&p=0&success_delete='.$success);
    exit();
}