<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\MessageTable;
use App\UserTable;
use App\Model\Message;
use App\Connection;

$pdo = Connection::getPDO(); 

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');


$messageTable= new MessageTable($pdo);

$cin=$_SESSION['id_user'];
$role=$_SESSION['role'];
$userTable=new UserTable($pdo);
$messages = $messageTable->getMessages($cin, $role);

// Séparer reçus et envoyés
$messagesRecus = array_filter($messages, fn($m) => $m->getCinDestinataire() == $cin);
$messagesEnvoyes = array_filter($messages, fn($m) => $m->getCinExpediteur() == $cin);

// Suppression d’un message
if (isset($_POST['delete_message'])) {
    $idMessage = (int) $_POST['delete_message'];
    $messageTable->supprimerMessage($idMessage);
    header('Location: ' . $router->url('admin-messagerie'));
    exit();
}
// Modification d’un message
if (isset($_POST['edit_message'])) {
    $idMessage = (int) $_POST['edit_message'];
    $nouvelObjet = trim($_POST['objet'] ?? '');
    $nouveauContenu = trim($_POST['contenu'] ?? '');
    if (!empty($nouvelObjet) && !empty($nouveauContenu)) {
        $messageTable->modifierMessage($idMessage, $nouvelObjet, $nouveauContenu);
        header('Location: ' . $router->url('admin-messagerie'));
        exit();
    }
}
// Répondre à un message
if (isset($_POST['reply_message']) && isset($_POST['cin_destinataire'])) {
    $destinataireCin = $_POST['cin_destinataire'];
    $objet = trim($_POST['objet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    if (!empty($objet) && !empty($contenu)) {
        $messageTable->envoyerMessage(
            $cin,
            $destinataireCin,
            'etudiant', // On répond à un étudiant
            $objet,
            $contenu
        );
        header('Location: ' . $router->url('admin-messagerie'));
        exit();
    }
}

// Envoi d’un message à un étudiant sélectionné
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['objet'], $_POST['cin_destinataire']) && !isset($_POST['edit_message'])) {
    $objet = trim($_POST['objet']);
    $contenu = trim($_POST['contenu']);
    $cinDestinataire = trim($_POST['cin_destinataire']);

    if (!empty($objet) && !empty($contenu) && !empty($cinDestinataire)) {
        $messageTable->envoyerMessage(
            $cin, // CIN de l’admin
            $cinDestinataire,
            'etudiant',
            $objet,
            $contenu
        );
        header('Location: ' . $router->url('admin-messagerie'));
        exit();
    }
}


?>

<div class="prof-list">
    <div class="intro-prof-list">
        <h1>MESSAGERIE</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="new-msg-btn">
        <button type="button" class="btn-nouveau-message">Nouveau message +</button>
    </div>
    <div class="new-msg-form"></div>

    <center>
    <form class="new-msg" method="post" action="<?= $router->url('admin-messagerie') ?>">
    <h2 class="messagerie-intro">Envoyer un message</h2>
    <!-- Sélection de l'étudiant destinataire -->
    <label for="cin_destinataire">Étudiant destinataire</label>
    <select name="cin_destinataire" required>
        <option value="">-- Sélectionner un étudiant --</option>
        <?php foreach ($userTable->getAllEtudiants() as $etudiant): ?>
            <option value="<?= htmlspecialchars($etudiant->getCIN()) ?>">
                <?= htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom() . ' (CIN: ' . $etudiant->getCIN() . ')') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="objet">Objet</label>
    <input type="text" name="objet" required>

    <label for="message">Message</label>
    <textarea name="contenu" required></textarea>

    <input type="submit" value="Envoyer">
    </form>
    </center>

    <div class="conteneur-messagerie">
        <h3 class="messagerie-intro">Messages reçus</h3><div class="hr"></div>
        <div class="msg">
            <?php if (empty($messagesRecus)): ?>
                <p>Aucun message reçu.</p>
            <?php else: ?>
                <ul class="liste-messages">
                    <?php foreach ($messagesRecus as $message): ?>
                        <li class="message">
                            <strong>De :</strong> <?= htmlspecialchars($userTable->findByCin($message->getCinExpediteur())->getNom() . " " . $userTable->findByCin($message->getCinExpediteur())->getPrenom()) ?><br>
                            <strong>Objet :</strong> <?= htmlspecialchars($message->getObjet()) ?><br>
                            <strong>Date :</strong> <?= htmlspecialchars($message->getDate()) ?><br>
                            <strong>Contenu :</strong> <?= nl2br(htmlspecialchars($message->getContenu())) ?>
                        </li>
                        <!-- Bouton Répondre -->
                        <button type="button" class="btn1" onclick="toggleReplyForm(<?= $message->getId() ?>)">Répondre</button>

                        <div id="reply-form-<?= $message->getId() ?>" style="display:none; margin-top:10px;">
                            <form method="post" class="edit-msg-form" action="<?= $router->url('admin-messagerie') ?>">
                                <input type="hidden" name="reply_message" value="<?= $message->getId() ?>">
                                <input type="hidden" name="cin_destinataire" value="<?= $message->getCinExpediteur() ?>">
                                <label>Objet :</label><br>
                                <input type="text" name="objet" value="Re: <?= htmlspecialchars($message->getObjet()) ?>"><br>
                                <label>Contenu :</label><br>
                                <textarea name="contenu"></textarea><br>
                                <button type="submit">Envoyer la réponse</button>
                            </form>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="conteneur-messagerie">
        <h3 class="messagerie-intro">Messages envoyés</h3><div class="hr"></div>
        <?php if (empty($messagesEnvoyes)): ?>
            <p>Aucun message envoyé.</p>
        <?php else: ?>
            <ul class="liste-messages">
                <?php foreach ($messagesEnvoyes as $message): ?>
                    <li class="message">
                        <strong>À :</strong> <?= htmlspecialchars($userTable->findByCin($message->getCinDestinataire())->getNom() . " " . $userTable->findByCin($message->getCinDestinataire())->getPrenom()) ?><br>
                        <strong>Objet :</strong> <?= htmlspecialchars($message->getObjet()) ?><br>
                        <strong>Date :</strong> <?= htmlspecialchars($message->getDate()) ?><br>
                        <strong>Contenu :</strong> <?= nl2br(htmlspecialchars($message->getContenu())) ?><br>

                        <!-- Boutons Modifier/Supprimer -->
                        <form method="post" action="<?= $router->url('admin-messagerie') ?>" style="display:inline;">
                            <input type="hidden" name="delete_message" value="<?= $message->getId() ?>">
                            <button type="submit" class="btn2" onclick="return confirm('Voulez-vous vraiment supprimer ce message ?')">Supprimer</button>
                        </form>

                        <!-- Formulaire de modification -->
                        <button type="button" class="btn1" onclick="toggleForm(<?= $message->getId() ?>)">Modifier</button>

                        <div id="edit-form-<?= $message->getId() ?>" style="display:none; margin-top:10px;">
                            <form method="post" class="edit-msg-form" action="<?= $router->url('admin-messagerie') ?>">
                                <input type="hidden"  name="edit_message" value="<?= $message->getId() ?>">
                                <label>Objet :</label><br>
                                <input type="text" name="objet" value="<?= htmlspecialchars($message->getObjet()) ?>"><br>
                                <label>Contenu :</label><br>
                                <textarea name="contenu"><?= htmlspecialchars($message->getContenu()) ?></textarea><br>
                                <button type="submit">Enregistrer</button>
                            </form>
                        </div>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>