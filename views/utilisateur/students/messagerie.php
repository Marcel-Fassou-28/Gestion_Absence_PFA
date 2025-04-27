<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'etudiant') {
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

// Récupérer les justificatifs envoyés par l'étudiant
$justificatifs = $pdo->prepare("
    SELECT j.dateSoumission, j.message, j.nomFichierJustificatif, j.statut, a.date AS dateAbsence, m.nomMatiere
    FROM Justificatif j
    JOIN Absence a ON j.idAbsence = a.idAbsence
    JOIN Matiere m ON a.idMatiere = m.idMatiere
    WHERE a.cinEtudiant = :cin
    ORDER BY j.dateSoumission DESC
");
$justificatifs->execute(['cin' => $cin]);
$justificatifs = $justificatifs->fetchAll(PDO::FETCH_ASSOC);


?>

 <div class="dshb-messagerie container">
    <div>
        <h2 class="messagerie-intro">MESSAGERIE</h2><div class="underline"></div>
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
        <div class="conteneur-messagerie">
            <h3 class="messagerie-intro ">Messages reçus</h3><div class="hr"></div>
            <div class="msg">
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
                        <hr>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            </div>
        </div>

        <div class="conteneur-messagerie">
            <h3 class="messagerie-intro ">Messages envoyés</h3><div class="hr"></div>
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
                        <hr>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="conteneur-messagerie">
            <h3 class="messagerie-intro">Justificatifs soumis</h3><div class="hr"></div>
            <?php if (empty($justificatifs)): ?>
            <p>Aucun justificatif soumis.</p>
            <?php else: ?>
                <ul class="liste-messages">
            <?php foreach ($justificatifs as $justificatif): ?>
                <li class="message">
                    <strong>Date de l'absence :</strong> <?= date('d/m/Y', strtotime($justificatif['dateAbsence'])) ?><br>
                    <strong>Matière :</strong> <?= htmlspecialchars($justificatif['nomMatiere']) ?><br>
                    <strong>Statut :</strong> <?= htmlspecialchars($justificatif['statut']) ?><br>
                    <strong>Message :</strong> <?= nl2br(htmlspecialchars($justificatif['message'])) ?><br>
                    <strong>Fichier :</strong> 
                    <a href="<?=$router->url('serve-justificatif').'?fichier='.urlencode($justificatif['nomFichierJustificatif']) ?>" target="_blank">
                    Voir fichier
                    </a>
                    <br><br>
                    <a href="<?=$router->url('etudiant-absences') .'?listprof=1&p=0'?>" class="btn-smtt">Voir plus</a>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $objet = trim($_POST['objet'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');

    if ($role === 'etudiant') {
        // Étudiant → tous les admins
        $admins=$userTable->getAllAdmins();
        foreach($admins as $admin){
            $messageTable->envoyerMessage(
            $cin,
            $admin->getCIN(),
            'admin',
            $objet,
            $contenu
        );
        }
      /*   
    } else {
        // Admin → un étudiant précis
        $cinDestinataire = (int)($_POST['destinataire'] ?? 0);
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
                $contenu
            );
        }*/
    }
}
