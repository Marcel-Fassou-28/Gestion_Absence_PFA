<?php
use App\MessageTable;
use App\UserTable;
use App\Model\Message;

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$idUtilisateur = $user['id'];
$role = $user['role'];

// Récupérer tous les messages pour l'utilisateur
$messages = $messageTable->getMessages($idUtilisateur, $role);

// Séparer reçus et envoyés
$messagesRecus = array_filter($messages, fn($m) => $m->getIdDestinataire() == $idUtilisateur);
$messagesEnvoyes = array_filter($messages, fn($m) => $m->getIdExpediteur() == $idUtilisateur);
?>

 <div class="messagerie-container">
        <h2>Messagerie</h2>
        <a href="nouveau-message.php" class="btn-nouveau-message">Nouveau Message</a>

        <div class="section-messages">
            <h3>Messages reçus</h3>
            <?php if (empty($messagesRecus)): ?>
                <p>Aucun message reçu.</p>
            <?php else: ?>
                <ul class="liste-messages">
                    <?php foreach ($messagesRecus as $message): ?>
                        <li class="message">
                            <strong>De :</strong> <?= htmlspecialchars($userTable->getUserNameById($message->getIdExpediteur())) ?><br>
                            <strong>Objet :</strong> <?= htmlspecialchars($message->getObjet()) ?><br>
                            <strong>Date :</strong> <?= htmlspecialchars($message->getDate()) ?><br>
                            <strong>Contenu :</strong> <?= nl2br(htmlspecialchars($message->getContenu())) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="section-messages">
            <h3>Messages envoyés</h3>
            <?php if (empty($messagesEnvoyes)): ?>
                <p>Aucun message envoyé.</p>
            <?php else: ?>
                <ul class="liste-messages">
                    <?php foreach ($messagesEnvoyes as $message): ?>
                        <li class="message">
                            <strong>À :</strong> <?= htmlspecialchars($userTable->getUserNameById($message->getIdDestinataire())) ?><br>
                            <strong>Objet :</strong> <?= htmlspecialchars($message->getObjet()) ?><br>
                            <strong>Date :</strong> <?= htmlspecialchars($message->getDate()) ?><br>
                            <strong>Contenu :</strong> <?= nl2br(htmlspecialchars($message->getContenu())) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
