<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Admin\StatisticAdmin;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$adminTable = new StatisticAdmin($pdo);
$allAdministrateur = $adminTable->getAllAdministrateur();
$numero = 1;

?>
<div class="prof-list">
    <?php if (isset($_GET['super_admin']) && $_GET['super_admin'] == '1'): ?>
        <div class="alert alert-danger">Vous devez etre le super administrateur pour effectuer cette opération ou Vous ne pouvez pas supprimer ce compte</div>
    <?php endif ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success">Les Infos Admin modifiée avec succès</div>

    <?php elseif(isset($_GET['success']) && $_GET['success'] == '0'): ?>

        <div class="alert alert-danger">Les Infos de cet Admin n'ont pas pu être modifiée</div>
    <?php endif ?>

    <?php if (isset($_GET['add']) && $_GET['add'] == '1'): ?>
        <div class="alert alert-success">Admin ajoutée avec succès</div>

        <?php elseif(isset($_GET['add']) && $_GET['add'] == '0'): ?>
            <div class="alert alert-danger">Aucun Admin ajouté</div>
    <?php endif ?>

    <?php if (isset($_GET['success_delete']) && $_GET['success_delete'] == '1'): ?>
        <div class="alert alert-success">Admin supprimé avec succès</div>

        <?php elseif(isset($_GET['success_delete']) && $_GET['success_delete'] == '0'): ?>
            
            <div class="alert alert-danger">Aucun admin supprimer</div>
    <?php endif ?>

    <div class="intro-prof-matiere">
        <h1> Liste Des Administrateur</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('admin-ajouter') . '?admin=1&p=0' ?>" class="btn-ajout">Ajouter un Administrateur</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="list-tri-table">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Cin Admin</th>
                    <th>Nom et Prenom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($allAdministrateur as $admin): ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($admin->getCIN()) ?></td>
                        <td><?= htmlspecialchars($admin->getNom() . ' ' . $admin->getPrenom()) ?></td>
                        <td><?= htmlspecialchars($admin->getEmail())?></td>
                        <td>
                            <a href="<?= $router->url('admin-modifie'). '?admin=1&p=0&id_admin='.$admin->getCIN() ?>" class="btn1">Modifier</a>
                            <a id="delete" href="<?= $router->url('admin-delete') .'?admin=1&p=0&id_admin='.$admin->getCIN() ?>" class="btn2">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>
</div>
