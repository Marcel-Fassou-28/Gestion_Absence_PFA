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

$messageTable= new MessageTable($pdo);

$cin=$_SESSION['id_user'];
$role=$_SESSION['role'];
$userTable=new UserTable($pdo);
$messages = $messageTable->getMessages($cin, $role);

// Séparer reçus et envoyés
$messagesRecus = array_filter($messages, fn($m) => $m->getCinDestinataire() == $cin);
$messagesEnvoyes = array_filter($messages, fn($m) => $m->getCinExpediteur() == $cin);

?>

 <div class="dashboard-messagerie container">
    <div>
        <h2 class="messagerie-intro">Messagerie</h2>
    </div>
    <div class="new-msg-btn">
    <button type="button" class="btn-nouveau-message">Nouveau message +</button>
    </div>
        
        <div class="new-msg-form"></div>
                <center>
                <form class="new-msg" method="post" action="<?=$router->url('etudiant-messagerie')?>">
                    <h2 class="messagerie-intro ">Envoyer un message</h2>
                    <label for="objet">Objet</label>
                    <input type="text" name="objet">
                    <label for="message">message</label>
                    <textarea name="contenu"></textarea>
                    <input type="submit" value="Envoyé">
                </form>
                </center>
        </div>
        <div class="dashboard-container">
            <h3 class="messagerie-intro ">Messages reçus</h3>
            <?php if (empty($messagesRecus)): ?>
                <p>Aucun message reçu.</p>
            <?php else: ?>
                <ul class="liste-messages">
                    <?php foreach ($messagesRecus as $message): ?>
                        <li class="message">
                            <strong>De :</strong> <?= htmlspecialchars($userTable->findByCin($message->getCinExpediteur())->getNom()." ". $userTable->findByCin($message->getCinExpediteur())->getPrenom() )?><br>
                            <strong>Objet :</strong> <?= htmlspecialchars($message->getObjet()) ?><br>
                            <strong>Date :</strong> <?= htmlspecialchars($message->getDate()) ?><br>
                            <strong>Contenu :</strong> <?= nl2br(htmlspecialchars($message->getContenu())) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="dashboard-container">
            <h3 class="messagerie-intro ">Messages envoyés</h3>
            <?php if (empty($messagesEnvoyes)): ?>
                <p>Aucun message envoyé.</p>
            <?php else: ?>
                <ul class="liste-messages">
                    <?php foreach ($messagesEnvoyes as $message): ?>
                        <li class="message">
                            <strong>À :</strong> <?= htmlspecialchars($userTable->findByCin($message->getCinDestinataire())->getNom()." ". $userTable->findByCin($message->getCinDestinataire())->getPrenom() ) ?><br>
                            <strong>Objet :</strong> <?= htmlspecialchars($message->getObjet()) ?><br>
                            <strong>Date :</strong> <?= htmlspecialchars($message->getDate()) ?><br>
                            <strong>Contenu :</strong> <?= nl2br(htmlspecialchars($message->getContenu())) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
</div>
