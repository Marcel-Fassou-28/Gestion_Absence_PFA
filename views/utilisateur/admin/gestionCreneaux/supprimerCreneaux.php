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

$creneau = $_GET['id_creneau'];
if (isset($creneau)) {
    $query = $pdo->prepare('DELETE FROM creneaux WHERE id = :id_creneau');
    $query->execute(['id_creneau' => $creneau]);
    $success = 1;
    header('location:' . $router->url('gestion-creneau') .'?listprof=1&p=0&success_delete='.$success);
    exit();
} else {
    $success = 0;
    header('location:' . $router->url('gestion-creneau') .'?listprof=1&p=0&success_delete='.$success);
    exit();
}

