<?php
use App\Connection;
use App\MessageTable;

session_start();

if (!isset($_SESSION['auth'])) {
    header('Location: /login');
    exit();
}

$pdo = Connection::getPDO();
$messageTable = new MessageTable($pdo);

$user = $_SESSION['auth'];
$idExpediteur = $user['id'];
$role = $user['role'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $objet = trim($_POST['objet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $idMessageParent = $_POST['message_id'] ?? null;

    if ($role === 'etudiant') {
        // Étudiant → tous les admins
        $messageTable->envoyerMessage(
            $idExpediteur,
            0,
            'admin',
            $objet,
            $contenu,
            $idMessageParent
        );
    } else {
        // Admin → un étudiant précis
        $idDestinataire = (int)($_POST['destinataire'] ?? 0);
        $typeDestinataire = $_POST['destinataire_role'] ?? 'etudiant';

        if (!$idDestinataire) {
            $errors[] = "Veuillez sélectionner un étudiant.";
        }

        if (empty($errors)) {
            $messageTable->envoyerMessage(
                $idExpediteur,
                $idDestinataire,
                $typeDestinataire,
                $objet,
                $contenu,
                $idMessageParent
            );
        }
    }

    if (empty($errors)) {
        header('Location: ' . $router->url('messagerie') . '?success=1');
        exit();
    }
}
