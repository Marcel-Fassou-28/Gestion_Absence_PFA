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
use App\Logger;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$success = null;
$super_admin = null;

$admin = $_GET['id_admin'];
if (isset($admin)) {
    $query = $pdo->prepare('
        SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin 
    ');
    $query->execute(['cinAdmin' => $admin]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $result = $query->fetch();

    if (!$result) {
        $success = 0;
        header('location:' . $router->url('liste-des-admin'). '?admin=1&p=0&success='.$success);
        exit();
    }

    if (!empty($_POST) && isset($_POST['submit-modifier'])) {
        $cinAdmin = $_POST['cinAdmin'];
        $nomAdmin = $_POST['nomAdmin'];
        $prenomAdmin = $_POST['prenomAdmin'];

        /* Impossible de supprimer le super administrateur, ici, c'est le premier admin */
        $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
        $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
        $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
        $admin_verifie = $query_verifie->fetch();

        if ($admin_verifie && (string) $admin_verifie->getIDAdmin() == '1') {
            $query1 = $pdo->prepare('UPDATE administrateur SET cinAdmin = :cinAdmin, nom = :nom, prenom = :prenom WHERE cinAdmin = :oldcinAdmin');
            $query1->execute([
                'cinAdmin' => $cinAdmin,
                'oldcinAdmin' => $cinAdmin,
                'nom' => $nomAdmin,
                'prenom' => $prenomAdmin
            ]);
            $query2 = $pdo->prepare('UPDATE utilisateur SET cin = :cinAdmin, nom = :nom, prenom = :prenom WHERE cin = :oldcinAdmin');
            $query2->execute([
                'cinAdmin' => $cinAdmin,
                'oldcinAdmin' => $cinAdmin,
                'nom' => $nomAdmin,
                'prenom' => $prenomAdmin
            ]);
            $success = 1;
            Logger::log("Modification des infos d'un Admin", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
            header('location:' . $router->url('liste-des-admin'). '?admin=1&p=0&success='.$success);
            exit();
        }else {
            $super_admin = 1;
            header('location:' . $router->url('liste-des-admin'). '?admin=1&p=0&super_admin='.$super_admin);
            exit();
        }
    }
}else {
    $success = 0;
    header('location:' . $router->url('liste-des-admin'). '?admin=1&p=0&success='.$success);
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
                    <input type="text" name="cinAdmin" id="cinAdmin" value="<?= htmlspecialchars($result->getCIN()) ?>">
                </div>
                <div>
                    <label for="nomAdmin">Nom de l'Admin</label>
                    <input type="text" name="nomAdmin" id="nomAdmin" value="<?= htmlspecialchars($result->getNom()) ?>">
                </div>
                <div>
                    <label for="prenomAdmin">Prenom de l'Admin</label>
                    <input type="text" name="prenomAdmin" id="prenomAdmin" value="<?= htmlspecialchars($result->getPrenom()) ?>">
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere" value="submit-modifier" name="submit-modifier">Modifier</button>
                <!--<button class="btn2" onclick="window.location.href=''">Annuler</button>-->
                <a href="<?=$router->url('liste-des-admin'). '?admin=1&p=0' ?>" class="btn2">Annuler</a>
            </section>

        </form>
    </div>
</div>
