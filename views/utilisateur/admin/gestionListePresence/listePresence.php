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
use App\Admin\adminTable;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$pdo = Connection::getPDO();
$tableAdmin = new adminTable($pdo);

$listeFichierPresence = $tableAdmin->getAllFichierListPresence();

?>


<div class="prof-list">
<div class="intro-prof-list">
        <h1>Fichiers de Listes de Presence</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
    <form action="" class="tri-list container" method="POST">
        <div class="list-classe">
            <select name="classe" id="tri">
                <option value="defaut">Classe</option>
                <!-- Ensemble des classes tiré de la base de données -- -->
            </select>
        </div>
        <div class="list-classe">
            <select name="classe" id="tri">
                <option value="defaut">Matiere</option>
                <!-- Affichage dynamique des matières en fonction de la classe par utilisation du javascript -->
            </select>
        </div>
        <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>
        </form>
    </div>
    <div class="list-tri-table-justificatif">
            <table>
                <thead>
                    <th>N°</th>
                    <th>Nom et Prenom du Professeur</th>
                    <th>Date de soumission</th>
                    <th>Classe</th>
                    <th>Action</th>
                </thead>
                <?php $numero = 1 ?>
                <?php foreach ($listeFichierPresence as $row) { ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($row->getNomPrenom()) ?></td>
                        <td><?= htmlspecialchars($row->getDate()) ?></td>
                        <td><?= htmlspecialchars($row->getClasse()) ?></td>
                        <td>
                            <a class="btn1" href="<?= $router->url('liste-presence-soumis-details') . '?file='. $row->getNomFichierPresence() ?>">Voir details</a>
                            <a class="btn2" href="<?= $router->url('liste-presence-soumis-delete'). '?file='. $row->getNomFichierPresence() ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>