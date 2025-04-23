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
use App\Admin\adminTable;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$success_delete = null;

$cinEtudiant = $_GET['cin'];
if (isset($cinEtudiant)) {
    if ($result->SuprimerStudent($cinEtudiant)) {
        $success_delete = 1;

    } else {
        $success_delete = 0;
    }
    header('location: ' . $router->url('liste-etudiants').'?listprof=1&p=0&success_delete='. $success_delete);
    exit();
} else {
    $success_delete = 0;
    header('location: ' . $router->url('liste-etudiants').'?listprof=1&p=0&success_delete='. $success_delete);
    exit();
}
