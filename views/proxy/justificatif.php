<?php

if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    exit("Accès interdit.");
}

if(isset($_SESSION['role']) && ($_SESSION['role'] != 'etudiant')){
    http_response_code(403);
    exit('Vous avez pas accés');
}



$file = urldecode($_GET['fichier']);

$photoPath = $file;
$destinationDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR. 'uploads' .DIRECTORY_SEPARATOR.'justificatif' . DIRECTORY_SEPARATOR . $photoPath;

if (file_exists($destinationDir)) {
    $found = true;
    $mimeType = mime_content_type($destinationDir); // détecte automatiquement le type MIME
    header("Content-Type: $mimeType");
    header("Content-Length: " . filesize($destinationDir));
    readfile($destinationDir);
    exit();
}


