<?php

if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    exit("Accès interdit.");
}

if(isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("Accès interdit.");
}

$nomFichier = $_GET['file'];
$dossier = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'presence' . DIRECTORY_SEPARATOR;
$cheminComplet = $dossier . $nomFichier;

// Vérifie si le fichier existe
if (!file_exists($cheminComplet)) {
    http_response_code(404);
    exit("Image non trouvée.");
}

// Détermine le type MIME et affiche l'image
$mimeType = mime_content_type($cheminComplet);
header("Content-Type: $mimeType");
header("Content-Length: " . filesize($cheminComplet));
readfile($cheminComplet);
exit();

