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
use App\Model\Administrateur;
use App\Logger;

$pdo = Connection::getPDO();
$success = null;


$admin = $_GET['id_admin'];
if (isset($admin)) {
    /* Impossible de supprimer le super administrateur, ici, c'est le premier admin */
    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();

    if ($admin_verifie && (string) $admin_verifie->getIDAdmin() == '1' && $admin != $_SESSION['id_user']) {

        $query = $pdo->prepare('DELETE FROM administrateur WHERE cinAdmin = :cinAdmin');
        $query->execute(['cinAdmin' => $admin]);

        $query = $pdo->prepare('DELETE FROM utilisateur WHERE cin = :cinAdmin');
        $query->execute(['cinAdmin' => $admin]);
        $success = 1;
        Logger::log("Suppression d'un Admin", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
    } else {
        $super_admin = 1;
        header('location:' . $router->url('liste-des-admin') .'?admin=1&p=0&super_admin=' . $super_admin);
        exit();
    }
} else {
    $success = 0;
}
header('location:' . $router->url('liste-des-admin') .'?admin=1&p=0&success_delete='.$success);
exit();