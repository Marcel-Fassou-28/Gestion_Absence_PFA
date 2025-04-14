<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Connection;
use App\UserTable;
use App\Utils\UtilsInformation;

$pdo = Connection::getPDO();
$table = new UserTable($pdo);
$utilsInfo = new UtilsInformation($pdo);
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
        <div class="statistic">
            <section class="last-absence">
                <h2>Derniere Absence</h2>
                <div class="hr"></div>
                
            </section>
            <section class="classe-stat">
                <h2>Statistic</h2>
                <div class="hr"></div>
                    <ul class="list-statistic">

                    </ul>
            </section>
            
        </div>
        <div class="hr"></div>
        <div class="link-section">
            <section class="container use-link">
                <h2>Liens Utiles</h2>
                <div class="hr"></div>
                
            </section>
            <section class="container historic">
                <h2>Historiques</h2>
                <div class="hr"></div>
                <ul class="historic-list">
                    <li><a href="<?= $router->url('etudiant-absences') . '?historic=absence' ?>">Historiques des Absences</a></li>
                </ul>
            </section>
        </div>
    </div>
</div>