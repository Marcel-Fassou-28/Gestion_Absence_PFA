<?php

if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    exit("Accès interdit.");
}

if(isset($_SESSION['role']) && $_SESSION['role'] !== 'etudiant') {
    http_response_code(403);
    exit("Accès interdit.");
}

use App\Connection;
use App\Admin\adminTable;

$cin = $_SESSION['id_user'];
$pdo = Connection::getPDO();
$result = new adminTable($pdo);


$file = $_GET['fichier'];

$photoPath = $file;
$destinationDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR. 'uploads' .DIRECTORY_SEPARATOR.'justificatif' . DIRECTORY_SEPARATOR . $photoPath;

if (!file_exists($destinationDir)) {
    http_response_code(404);
    exit("Image non trouvée.");
}

$mimeType = mime_content_type($destinationDir); // détecte automatiquement le type MIME
header("Content-Type: $mimeType");
header("Content-Length: " . filesize($destinationDir));
readfile($destinationDir);
exit();

