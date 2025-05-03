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


if (isset($_GET['idjustificatif'])){

    $id = $_GET['idjustificatif'];
    $sql = "UPDATE justificatif SET statut = 'refusé' WHERE idJustificatif = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    Logger::log("Refus d'un justificatif", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
    header('Location: ' . $router->url('justification').'?listprof=1&p=0');
    exit();

} else {
    header('Location: ' . $router->url('justification').'?listprof=1&p=0');
    exit();
}
?>