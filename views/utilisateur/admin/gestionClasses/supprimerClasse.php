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

$pdo = Connection::getPDO();
$success = null;


$classe = $_GET['id_classe'];
if (isset($classe)) {

    /* Impossible de supprimer le super administrateur, ici, c'est le premier admin */
    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();

    if ((string) $admin_verifie->getIDAdmin() != '1') {
        header('location:' . $router->url('gestion-classe') .'?classe=1&p=0&super_admin=1');
        exit();
    } else {
        $query = $pdo->prepare('DELETE FROM classe WHERE idClasse = :idClasse');
        $query->execute(['idClasse' => $classe]);
        $success = 1;
    }
} else {
    $success = 0;
}
header('location:' . $router->url('gestion-classe') .'?admin=1&p=0&success_delete='.$success);
exit();