<?php

if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Mailer;
use App\Model\Administrateur;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$mailer = new Mailer();
$cinAdmin = $_SESSION['id_user'];
$success = null;
$super_admin = null;
$error = 0;

if (!empty($_POST)) {
    $cinAdmin = $_POST['cinAdmin'];
    $nomAdmin = $_POST['nomAdmin'];
    $prenomAdmin = $_POST['prenomAdmin'];
    $emailAdmin = filter_var($_POST['emailAdmin'], FILTER_VALIDATE_EMAIL);

    $password = $_POST['password'];

    /* Impossible d'ajouter si vous n'etes pas le super administrateur, ici, c'est le premier admin */
    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $cinAdmin]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();
    
    if ($admin_verifie) {
        if((string) $admin_verifie->getIDAdmin() === '1') {
            $query1 = $pdo->prepare('INSERT INTO administrateur(cinAdmin, nom, prenom, email) VALUES ( ?, ?, ?, ?');
            $query1->execute([ $cinAdmin, $nomAdmin, $prenomAdmin, $emailAdmin]);

            $query2 = $pdo->prepare('INSERT INTO utilisateur(username, cin, nom, prenom, email, password, nomPhoto, role) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?');
            $query2->execute([$cinAdmin . $nomAdmin, $cinAdmin, $nomAdmin, $prenomAdmin, $emailAdmin, password_hash($password, PASSWORD_BCRYPT), "avatar.png", "admin"]);
            $mailer->confirmationAdminAccount($nomAdmin . ' ' . $prenomAdmin, $emailAdmin, $cinAdmin . $nomAdmin, $password, $emailAdmin);
            $success = 1;
            exit();
        } 
    }
    $super_admin = 1;
    exit();
}
?>

<div class="prof-list">
    <div class="intro-prof-matiere">
        <h1> Modifier les Informations d'un Admin</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="matiere-modifie container" method="POST">
            <section class="edit-matiere-section">
                <div>
                    <label for="cinAdmin">CIN Admin</label>
                    <input type="text" name="cinAdmin" id="cinAdmin" value="" required>
                </div>
                <div>
                    <label for="nomAdmin">Nom de l'Admin</label>
                    <input type="text" name="nomAdmin" id="nomAdmin" value="" required>
                </div>
                <div>
                    <label for="prenomAdmin">Prenom de l'Admin</label>
                    <input type="text" name="prenomAdmin" id="prenomAdmin" value="" required>
                </div>
                <div>
                    <label for="emailAdmin">Email de l'admin</label>
                    <input type="email" name="emailAdmin" id="emailAdmin" value="" required>
                </div>
                <div>
                    <label for="password">Mot de Passe par defaut</label>
                    <input type="password" name="password" id="password" value="" required>
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere">Ajouter</button>
                <button class="btn2" onclick="window.location.href='<?=$router->url('liste-des-admin'). '?admin=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>
