<?php


if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Admin\adminTable;
use App\Logger;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$success_delete = 5;

$idFiliere = $_GET['id'];

if (isset($idFiliere)) {

    if ($result->SuprimerFiliere($idFiliere)) {
        $success_delete = 1;
        Logger::log("Suppression d'une filiere", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);

    } else {
        $success_delete = 0;

    }
    header('location: ' . $router->url('liste-filiere-admin') . '?listprof=1&p=0&delete_success=' . $success_delete);
    exit();
} else {

    $success_delete = 0;
    header('location: ' . $router->url('liste-filiere-admin') . '?listprof=1&p=0&delete_success=' . $success_delete);
    exit();
}
