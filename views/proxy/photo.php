<?php

if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    exit("Accès interdit.");
}

use App\Connection;
use App\UserTable;

$cin = $_SESSION['id_user'];
$pdo = Connection::getPDO();
$userTable = new UserTable($pdo);
$user = $userTable->getIdentification($cin);

// Vérifie que l'utilisateur demande SA PROPRE photo
if ($user->getCIN() != $cin) {
    http_response_code(403);
    exit("Accès non autorisé.");
}

$photoPath = $user->getNomPhoto() ?: 'avatar.png';
$destinationDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR. 'uploads' .DIRECTORY_SEPARATOR.'profil' . DIRECTORY_SEPARATOR . $photoPath;

if (file_exists($destinationDir)) {
    $found = true;
    $mimeType = mime_content_type($destinationDir); // détecte automatiquement le type MIME
    header("Content-Type: $mimeType");
    header("Content-Length: " . filesize($destinationDir));
    readfile($destinationDir);
    exit();
}

