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
use App\Logger;

$pdo = Connection::getPDO();

$success = null;
$filename = $_GET['file'];
$filePath = dirname(__DIR__, 4) .DIRECTORY_SEPARATOR. 'uploads'.DIRECTORY_SEPARATOR.'presence' . DIRECTORY_SEPARATOR . $filename;

if (file_exists($filePath)) {
    unlink($filePath);
    $query = $pdo->prepare('DELETE FROM listePresence WHERE nomFichierPresence = :file');
    $query->execute(['file' => $filename]);
    $success = 1;
    Logger::log("Suppression d'une fiche de presence", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
    header('location: ' . $router->url('liste-presence-soumis') .'?listprof=1&p=0&success='.$success);
    exit();
    
} else {
    $success = 0;
    header('location: ' . $router->url('liste-presence-soumis') .'?listprof=1&p=0&success='.$success);
    exit();
}

?>