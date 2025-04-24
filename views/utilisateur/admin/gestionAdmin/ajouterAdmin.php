<?php

if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\connection;
use App\Model\Administrateur;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$success = null;
$super_admin = null;
$error = 0;

if (!empty($_POST)) {
    $cinAdmin = $_POST['cinAdmin'];
    $nomAdmin = $_POST['nomAdmin'];
    $prenomAdmin = $_POST['prenomAdmin'];
    $password = $_POST['password'];

    /* Impossible d'ajouter si vous n'etes pas le super administrateur, ici, c'est le premier admin */
    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $admin]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();

    if ((string) $admin_verifie->getIDAdmin() === '1') {
        $query1 = $pdo->prepare('INSERT INTO administrateur(cinAdmin, nom, prenom) VALUES ( ?, ?, ?');
        $query1->execute([ $cinAdmin, $nomAdmin, $prenomAdmin]);

        $query2 = $pdo->prepare('INSERT INTO utilisateur(username, cin, nom, prenom, password, nomPhoto, role) VALUES ( ?, ?, ?, ?, ?, ?, ?');
        $query2->execute([$cinAdmin . $nomAdmin, $cinAdmin, $nomAdmin, $prenomAdmin, password_hash($password, PASSWORD_BCRYPT), "avatar.png", "admin"]);
        
        $success = 1;
        header('location:' . $router->url('liste-des-admin'). '?admin=1&p=0&success='.$success);
        exit();
    }else {
        $super_admin = 1;
        header('location:' . $router->url('liste-des-admin'). '?admin=1&p=0&super_admin='.$super_admin);
        exit();
    }
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
                    <input type="text" name="cinAdmin" id="cinAdmin" value="">
                </div>
                <div>
                    <label for="nomAdmin">Nom de l'Admin</label>
                    <input type="text" name="nomAdmin" id="nomAdmin" value="">
                </div>
                <div>
                    <label for="prenomAdmin">Prenom de l'Admin</label>
                    <input type="text" name="prenomAdmin" id="prenomAdmin" value="">
                </div>
                <div>
                    <label for="password">Mot de Passe par defaut</label>
                    <input type="password" name="password" id="password" value="">
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere">Ajouter</button>
                <button class="btn2" onclick="window.location.href='<?=$router->url('liste-des-admin'). '?admin=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>
