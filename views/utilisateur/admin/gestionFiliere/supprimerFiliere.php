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
use App\Model\Administrateur;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$success_delete = 5;

$idFiliere = $_GET['id'];
if (isset($idFiliere)) {
    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();
     
    if ($admin_verifie && $admin_verifie->getIDAdmin() == '1') {
        if ($result->SuprimerFiliere($idFiliere)) {
            $success_delete = 1;
            Logger::log("Suppression d'une filiere", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
        } else {
            $success_delete = 0;
        }
        header('location: ' . $router->url('liste-filiere-admin') . '?listprof=1&p=0&delete_success=' . $success_delete);
        exit();
    } else {
        $super_admin = 1;
        header('location:' . $router->url('liste-filiere-admin'). '?listprof=1&p=0&super_admin='.$super_admin);
        exit();
    }
} else {
    $success_delete = 0;
    header('location: ' . $router->url('liste-filiere-admin') . '?listprof=1&p=0&delete_success=' . $success_delete);
    exit();
}
