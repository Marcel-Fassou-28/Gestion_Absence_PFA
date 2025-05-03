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
use App\Logger;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$success_delete = null;

$cinProf = $_GET['cin'];
if (isset($cinProf)) {
    if ($result->SuprimerProf($cinProf)) {
        $success_delete = 1;
        Logger::log("Suppression des infos d'un prof", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
    } else {
        $success_delete = 0;
    }
    header('location: '.$router->url('liste-professeur').'?listprof=1&p=0&delete_success='.$success_delete);
    exit();
} else {
    $success_delete = 0;
    header('location: '.$router->url('liste-professeur').'?listprof=1&p=0&delete_success='.$success_delete);
        exit();
}
