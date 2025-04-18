<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Connection;
use App\UserTable;
use App\Utils\UtilsInformation;
use App\Model\Utils\LastAbsence;

$pdo = Connection::getPDO();
$table = new UserTable($pdo);
$utilsInfo = new UtilsInformation($pdo);
$cinProf = $_SESSION['id_user'];

$user = $table->getIdentification($cinProf);
$creneaux = $utilsInfo->getAllCreneaux($cinProf);
$lastInfoAbsence = $utilsInfo->getInfoDerniereAbsence($cinProf);
$statistiquesClasses = $utilsInfo->getInfoEffectifsNbrAbsents($cinProf);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$moisEnFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
    'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
    'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');
$dateSql = $date->format('Y-m-d H:i');

$currentDay = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'][$date->format('l')];
$currentTime = $date->format('H:i:s');
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
                <h2>Derniere Absence Effectuées sur Site</h2>
                <div class="hr"></div>
                <ul class="list-info-absence">
                <?php if ($lastInfoAbsence instanceof LastAbsence): ?>
            <li class="absence-item">
                <div class="absence-detail">
                    <span class="absence-label">Classe :</span>
                    <span class="absence-value"><?= htmlspecialchars($lastInfoAbsence->getNomClasse() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Filière :</span>
                    <span class="absence-value"><?= htmlspecialchars($lastInfoAbsence->getNomFiliere() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Matière :</span>
                    <span class="absence-value"><?= htmlspecialchars($lastInfoAbsence->getNomMatiere() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Date :</span>
                    <span class="absence-value"><?= $lastInfoAbsence->getDate() ? date('d/m/Y H:i', strtotime($lastInfoAbsence->getDate())) : 'Non spécifiée' ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Créneau :</span>
                    <span class="absence-value">
                        <?= $lastInfoAbsence->getHeureDebut() && $lastInfoAbsence->getHeureFin() 
                            ? substr($lastInfoAbsence->getHeureDebut(), 0, 5) . ' - ' . substr($lastInfoAbsence->getHeureFin(), 0, 5) 
                            : 'Non spécifié' ?>
                    </span>
                </div>
                
            </li>
        <?php else: ?>
            <li class="absence-item absence-empty">
                <span>Aucune absence enregistrée.</span>
            </li>
        <?php endif; ?>
                </ul>
            </section>
            <section class="creneaux">
                <h2>Créneaux</h2>
                <div class="hr"></div>
                <ul class="list-creneaux">
                    <?php foreach ($creneaux as $jour => $c): ?>
                        <li class="creneau-day">
                            <span class="day-title"><?= htmlspecialchars($jour) ?></span>
                            <ul class="creneau-list">
                                <?php foreach ($c as $creneau): $isCurrent = ($jour === $currentDay && $currentTime >= $creneau->getHeureDebut() && $currentTime <= $creneau->getHeureFin()); ?>
                                    <li class="creneau-item <?= $isCurrent ? 'creneau-current' : '' ?>">
                                        <span class="creneau-time">
                                            <?= substr($creneau->getHeureDebut(), 0, 5) ?> - <?= substr($creneau->getHeureFin(), 0, 5) ?>
                                            <?= $isCurrent ? '<span class="current-marker">En cours</span>' : '' ?>
                                        </span>
                                        <span class="creneau-info">
                                            <?= htmlspecialchars($creneau->getNomMatiere() ?? 'Matière inconnue') ?>
                                            (Classe: <?= htmlspecialchars($creneau->getNomClasse() ?? 'Non spécifiée') ?>)
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <section class="classe-stat">
                <h2>Statistiques</h2>
                <div class="hr"></div>
                <ul class="list-statistic">
                <?php if (!empty($statistiquesClasses) && is_array($statistiquesClasses)): ?>
                        <?php foreach ($statistiquesClasses as $stat): ?>
                            <li class="stat-item">
                                <div class="stat-detail">
                                    <span class="stat-label">Classe :</span>
                                    <span class="stat-value"><?= htmlspecialchars($stat->getNomClasse() ?? 'Non spécifiée') ?></span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-label">Effectif :</span>
                                    <span class="stat-value"><?= htmlspecialchars($stat->getEffectifTotal() ?? 0) ?></span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-label">Absents :</span>
                                    <span class="stat-value"><?= htmlspecialchars($stat->getTotalAbsents() ?? 0) ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="stat-item stat-empty">
                            <span>Aucune statistique disponible.</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </section>
        </div>
        <div class="hr"></div>
        <div class="link-section">
            <section class="container use-link">
                <h2>Liens Utiles</h2>
                <div class="hr"></div>
                <ul class="use-link-list">
                    <li><a href="<?= $router->url('professor-listePresence') . '?use-link=student-presence' ?>">Effectuer la Présence</a></li>
                    <li><a href="<?= $router->url('professor-listeEtudiant') . '?use-link=student-list' ?>">Listes des Etudiants</a></li>
                </ul>
            </section>
            <section class="container historic">
                <h2>Historiques</h2>
                <div class="hr"></div>
                <ul class="historic-list">
                    <li><a href="<?= $router->url('historic-absence') . '?historic=absence' ?>">Historiques des Absences</a></li>
                </ul>
            </section>
        </div>
    </div>
</div>