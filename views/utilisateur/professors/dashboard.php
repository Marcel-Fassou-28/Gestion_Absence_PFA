<?php
use App\Connection;
use App\UserTable;

$pdo = Connection::getPDO();
$table = new UserTable($pdo);
$user = $table->getIdentification($_SESSION['id_user']);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$moisEnFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
    'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
    'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');
$dateSql = $date->format('Y-m-d H:i:s');
?>

<div class="container dashboard">
    <div class="dashboard-intro">
        <h1>Tableau de Bord</h1>
        <span class="user-info"><?= $_SESSION['role'] === 'etudiant' ? 'Bonjour ' : 'Bonjour Mr. '?> <?= htmlspecialchars($user->getNom()) .' '. htmlspecialchars($user->getPrenom()) ?></span>
        <span class="date-today"><?= $dateSql ?></span>
    </div>
    <div class="dashboard-container">
        <section class="container use-link">
            <h2>Liens Utiles</h2>
            <div class="hr"></div>
            <ul class="use-link-list">
                <li><a href="<?= $router->url('professor-calendrier') . '?use-link=calendrier' ?>">Calendrier</a></li>
                <li><a href="<?= $router->url('professor-listePresence') . '?use-link=student-presence' ?>">Effectuer la Présence</a></li>
                <li><a href="<?= $router->url('professor-listeEtudiant') . '?use-link=student-list' ?>">Listes des Etudiants</a></li>
                <li><a href="<?= $router->url('professor-autreInfo') . '?use-link=other' ?>">Autres Informations Supplementaires</a></li>
            </ul>
        </section>
        <section class="container historic">
            <h2>Historiques</h2>
            <div class="hr"></div>
            <ul class="historic-list">
                <li><a href="<?= $router->url('historic-absence') . '?historic=absence' ?>">Historiques des Absences</a></li>
                <li><a href="<?= $router->url('historic-stats') . '?historic=stats' ?>">Statistiques</a></li>
            </ul>
        </section>
    </div>
</div>