<?php

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

use App\Mailer;
use App\Connection;
use App\Logger;
use App\UserTable;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$cinUser = $_SESSION['id_user'];

$pdo = Connection::getPDO();
$userTable = new UserTable($pdo);
$mailer = new Mailer();
$infoUser = $userTable->getIdentification($cinUser);
$success = null;

if (!empty($_POST['message']))  {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = $_POST['message'];
    
    $success = $mailer->signalerProbleme($nom . ' ' .$prenom, $email, $message);
    Logger::log($message, 1, 'INFO', $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
}

?>

<div class="prof-list">
    <?php if (isset($success) && $success == true): ?>
        <div class="alert alert-success">Votre Message à bien été envoyé</div>
    <?php elseif(isset($success) && $success == false): ?>
        <div class="alert alert-danger">Votre Message n'a pas pu etre envoyé</div>
    <?php endif ?>
    <div class="intro-prof-matiere">
        <h1> Signaler un Problème</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="matiere-modifie container" method="post">
            <section class="edit-matiere-section">
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($infoUser->getNom()) ?>" readonly>
                </div>
                <div>
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($infoUser->getPrenom()) ?>" readonly>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($infoUser->getEmail()) ?>" readonly>
                </div>
                <div>
                    <label for="prenomAdmin">Parler du problème rencontré</label>
                    <textarea name="message" id="text-message" required></textarea>
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere">Envoyer</button>
            </section>

        </form>
    </div>
</div>

<style>
    textarea {
        width: 90%;
        height: 150px;
        color: #333;
    }
</style>
