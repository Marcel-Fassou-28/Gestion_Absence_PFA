<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

use App\Admin\StatisticAdmin;
use App\Connection;
use App\UserTable;
use App\Utils\InformationUtils;
use App\Model\Utils\LastAbsence;
use App\EtudiantTable;
use App\Model\Utils\Admin\InformationActifs;
use App\Model\Utils\Etudiant\DerniereAbsenceEtudiant;
use App\Professeur\CurrentInfo;

$pdo = Connection::getPDO();
$table = new UserTable($pdo);
$cin = $_SESSION['id_user'];

$user = $table->getIdentification($cin);



//Informations qui intéressent les professeurs
if ($_SESSION['role'] === 'professeur') {
    $utilsInfo = new InformationUtils($pdo);
    $currentProfInfo = new CurrentInfo($pdo);

    $creneaux = $utilsInfo->getAllCreneaux($cin);
    $lastInfoAbsence = $utilsInfo->getInfoDerniereAbsence($cin);
    $statistiquesClasses = $utilsInfo->getInfoEffectifsNbrAbsents($cin);
}

//Informations qui intéressent les etudiants
if ($_SESSION['role'] === 'etudiant') {
    $etudiantInfo = new EtudiantTable($pdo);

    $creneauProfesseurs = $etudiantInfo->getAllCreneauxProf($cin);
    $infoEtudiant = $etudiantInfo->getInfoGeneralEtudiant($cin);
    $absenceParMatiere = $etudiantInfo->getStatistiqueAbsenceEtudiant($cin);
}

if ($_SESSION['role'] === 'admin') {
    $adminTable = new StatisticAdmin($pdo);

    $statisticFiliere = $adminTable->getStatisticFiliere();
    $infoGeneral = $adminTable->getInformationGenerale();
    $derniereAbsencesEffectue = $adminTable->getLastAbsenceSend();
    $listPresence = $adminTable->getIfAbsenceByFile();
}

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

<!-- Section pour professeur -->

<div class="container dashboard">
    <div class="dashboard-intro">
        <h1>Tableau de Bord</h1>
        <span class="user-info"><?= $_SESSION['role'] === 'etudiant' ? 'Bonjour ' : 'Bonjour Mr. '?> <?= htmlspecialchars($user->getNom()) .' '. htmlspecialchars($user->getPrenom()) ?></span>
        <span class="date-today"><?= $dateSql ?></span>
    </div>
    <?php if ($_SESSION['role'] === 'professeur'): ?>
    <div class="dashboard-container">
    <?php if (isset($_GET['error_prof']) && $_GET['error_prof'] == 1): ?>
        <div class="alert alert-danger">
            Vous n'etes pas autorisez à accéder à la liste des présences pour le moment, revenez plus tard, selon votre emploi du temps
        </div> 
    <?php endif ?>

    <?php if (isset($_GET['error_presence']) && $_GET['error_presence'] == 1): ?>
        <div class="alert alert-danger">
            Vous avez déjà effectué une absence pour cette matière
    <?php endif ?>

    <?php if (isset($_GET['error_presence_file']) && $_GET['error_presence_file'] == 1): ?>
        <div class="alert alert-danger">
            Vous avez déjà effectué envoyé le fichier de présence
    <?php endif ?>
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
                <?php if (date('w') !== '0'): ?>
                <div class="absence-detail">
                    <span class="absence-label">Etat d'Absence :</span>
                    <span class="absence-value">
                        <?= $currentProfInfo->hasAlreadyTakenAbsence($cin) ? 'Déjà effectué pour ce créneau' : 'Vous devez effectuer l\'absence' ?>
                    </span>
                </div>
                <?php if (!$currentProfInfo->hasAlreadySendListPresence($cin)): ?>
                    <div class="absence-detail">
                    <span class="absence-label">Etat Fiche d'Absence :</span>
                    <span class="absence-value">Vous n'avez pas encore envoyé de fiche d'absence</span>
                </div>
                <?php endif ?>
                <?php endif ?>
                
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
                    <li><a href="<?= $router->url('professor-listeEtudiant') . '?use-link=student-list&p=0' ?>">Listes des Etudiants</a></li>
                </ul>
            </section>
            <section class="container historic">
                <h2>Historiques</h2>
                <div class="hr"></div>
                <ul class="historic-list">
                    <li><a href="<?= $router->url('historic-absence') . '?historic=absence&p=0' ?>">Historiques des Absences</a></li>
                </ul>
            </section>
        </div>
    </div>

    <!-- Section des etudiants -->
    <?php elseif ($_SESSION['role'] === 'etudiant'): ?>
        <div class="dashboard-container">
        <div class="statistic">
            <section class="last-absence">
                <h2>Information Générale</h2>
                <div class="hr"></div>
                <ul class="list-info-absence">
                <?php if ($infoEtudiant instanceof DerniereAbsenceEtudiant): ?>
            <li class="absence-item">
                <div class="absence-detail">
                    <span class="absence-label">Département :</span>
                    <span class="absence-value"><?= htmlspecialchars($infoEtudiant->getNomDepartement() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Filière :</span>
                    <span class="absence-value"><?= htmlspecialchars($infoEtudiant->getNomFiliere() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Classe :</span>
                    <span class="absence-value"><?= htmlspecialchars($infoEtudiant->getNomClasse() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Dernière Absence</span>
                    <span class="absence-value">
                        <?= $infoEtudiant->getDateDerniereAbsence() ? $infoEtudiant->getDateDerniereAbsence() . ' en ' . $infoEtudiant->getNomMatiere()  : 'Non spécifié' ?>
                    </span>
                </div>
                
            </li>
        <?php else: ?>
            <li class="absence-item absence-empty">
                <span>Aucune information enregistrée.</span>
            </li>
        <?php endif; ?>
                </ul>
            </section>
            <section class="creneaux">
                <h2>Créneaux</h2>
                <div class="hr"></div>
                <ul class="list-creneaux">
                    <?php foreach ($creneauProfesseurs as $jour => $c): ?>
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
                                            <?= htmlspecialchars($creneau->getNomMatiere() ?? 'Non inconnue') ?>
                                            (Professeur: <?= htmlspecialchars($creneau->getNomProfesseur() ?? 'Non spécifiée') ?>)
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
                <?php if (!empty($absenceParMatiere) && is_array($absenceParMatiere)): ?>
                        <?php foreach ($absenceParMatiere as $absenceM): ?>
                            <li class="stat-item">
                                <div class="stat-detail">
                                    <span class="stat-label">Matière :</span>
                                    <span class="stat-value"><?= htmlspecialchars($absenceM->getNomMatiere() ?? 'Non spécifiée') ?></span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-label">Nombre d'Absence :</span>
                                    <span class="stat-value"><?= htmlspecialchars($absenceM->getNombreAbsences() ?? 'Non spécifiée') ?></span>
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
                    <li><a href="<?= $router->url('etudiant-messagerie').'?messagerie=1&listprof=1' ?>">Ma Messagerie</a></li>
                    <li><a href="<?= $router->url('liste-etudiant-classe', ['id' => $_SESSION['id_user']]) .'?use-link=student-list'?>">Listes des Etudiants de ma classe</a></li>
                </ul>
            </section>
            <section class="container historic">
                <h2>Historiques</h2>
                <div class="hr"></div>
                <ul class="historic-list">
                    <li><a href="<?= $router->url('etudiant-absences').'?listprof=1'?>">Historiques de mes Absences</a></li>
                </ul>
            </section>
        </div>
    </div>

    <!-- Section pour Administrateur -->
    <?php else: ?>
        <div class="dashboard-container">
        <div class="statistic">
            <section class="last-absence">
                <h2>Information Générale</h2>
                <div class="hr"></div>
                <ul class="list-info-absence">
                <?php if ($infoGeneral instanceof InformationActifs): ?>
            <li class="absence-item">
                <div class="absence-detail">
                    <span class="absence-label">Nombre Total Inscrit :</span>
                    <span class="absence-value"><?= htmlspecialchars($infoGeneral->getTotalInscrits() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Nombre Total Absents :</span>
                    <span class="absence-value"><?= htmlspecialchars($infoGeneral->getTotalAbsents() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Professeurs Actuellement Actifs: </span>
                    <span class="absence-value"><?= htmlspecialchars($infoGeneral->getProfesseursActifsActuellement() ?? 'Non spécifiée') ?></span>
                </div> 
                <div class="absence-detail">
                    <span class="absence-label">Professeurs Actuellement Absents: </span>
                    <span class="absence-value"><?= htmlspecialchars($infoGeneral->getProfesseursAbsentsActuellement() ?? 'Non spécifiée') ?></span>
                </div>
                <div class="absence-detail">
                    <span class="absence-label">Presence Effectuée en Classe: </span>
                    <span class="absence-value"><?= htmlspecialchars( $listPresence->getNombreListesSoumisesAujourdHui() == 0 ? 'Aucun fichier Emit' : $listPresence->getNombreListesSoumisesAujourdHui() . '🔔')  ?></span>
                </div>                  
            </li>
        <?php else: ?>
            <li class="absence-item absence-empty">
                <span>Aucune information Disponible.</span>
            </li>
        <?php endif; ?>
                </ul>
            </section>
            <section class="creneaux">
                <h2>Dernière Absence Effectuée</h2>
                <div class="hr"></div>
                <ul class="list-creneaux">
                <?php foreach ($derniereAbsencesEffectue as $class): ?>
                        <li class="creneau-day">
                            <span class="day-title"><?= htmlspecialchars($class->getNomClasse()) ?></span>
                            <ul class="creneau-list">
                                    <li class="creneau-item">
                                        <span class="creneau-time">
                                            <?=htmlspecialchars($class->getNomMatiere())?>
                                        </span>
                                        <span class="creneau-info">
                                            <?= "Nombre d'Absent:"  ?>
                                            <?= htmlspecialchars($class->getNombreAbsents() ?? 'Non spécifiée') ?>
                                        </span>
                                    </li>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="classe-stat">
                <h2>Statistiques des Filières</h2>
                <div class="hr"></div>
                <ul class="list-statistic">
                <?php if (!empty($statisticFiliere) && is_array($statisticFiliere)): ?>
                        <?php foreach ($statisticFiliere as $stat): ?>
                            <li class="stat-item">
                                <div class="stat-detail">
                                    <span class="stat-label">Filière :</span>
                                    <span class="stat-value"><?= htmlspecialchars($stat->getNomFiliere() ?? 'Non spécifiée') ?></span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-label">Effectif Total :</span>
                                    <span class="stat-value"><?= htmlspecialchars($stat->getTotalEtudiants() ?? 0) ?></span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-label">Nombre d'Absents :</span>
                                    <span class="stat-value"><?= htmlspecialchars($stat->getTotalAbsences() ?? 0) ?></span>
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
                    <li><a href="<?= $router->url('admin-messagerie') ?>"> Messageries </a></li>
                    <li><a href="<?= $router->url('justification'). '?listprof=1&p=0' ?>">Justificatifs</a></li>
                    <li><a href="<?= $router->url('liste-presence-soumis') . '?listprof=1&p=0'?>">Liste de Presence Soumis</a></li>
                    <li><a href="<?= $router->url('historikAbscences') .'?listprof=1&p=0' ?>">Absence des Etudiants</a></li>
                    <li><a href="<?= $router->url('gestion-creneau').'?listprof=1&p=0' ?>">Gestion des Créneaux</a></li>
                    <li><a href="<?= $router->url('liste-matiere-admin').'?matiere=1&p=0'?>">Gestion des Matieres et des Cours</a></li>
                </ul>
            </section>
            <section class="container historic">
                <h2>Gestion du Personnel</h2>
                <div class="hr"></div>
                <ul class="historic-list">
                    <li><a href="<?= $router->url('liste-etudiants').'?listprof=1&p=0' ?>">Gestion des étudiants</a></li>
                    <li><a href="<?= $router->url('liste-professeur').'?listprof=1&p=0'?>">Gestion des professeurs</a></li>
                    <li><a href="<?= $router->url('liste-des-admin').'?admin=1&p=0'?>">Gestion des Administrateurs</a></li>
                    <li><a href="<?= $router->url('gestion-classe').'?classe=1&p=0' ?>">Gestion des Classes</a></li>
                    <li><a href="<?= $router->url('liste-filiere-admin').'?listprof=1&p=0'?>">Gestion des filières</a></li>
                </ul>
            </section>
        </div>
    </div>
    
<style>
    .use-link-list li:nth-child(1) a::before {
    content: '\1F4E7'; 
    margin-right: 0.5rem;
}
.use-link-list li:nth-child(2) a::before {
    content: '\1F5C4'; 
    margin-right: 0.5rem;
}
.use-link-list li:nth-child(3) a::before {
    content: '\1F464'; 
    margin-right: 0.5rem;
}

.use-link-list li:nth-child(4) a::before {
    content: '\1F4C8'; 
    margin-right: 0.5rem;
}

.historic-list li:nth-child(1) a::before {
    content: '\1F393'; 
    margin-right: 0.5rem;
}
.historic-list li:nth-child(2) a::before {
    content: "\1F9D1\200D\1F3EB"; 
    margin-right: 0.5rem;
}
.historic-list li:nth-child(4) a::before {
    content: "\1F5C2"; 
    margin-right: 0.5rem;
}
.historic-list li:nth-child(3) a::before {
    content: '\1F464'; 
    margin-right: 0.5rem;
}
.historic-list li:nth-child(5) a::before {
    content: '\1F4DA'; 
    margin-right: 0.5rem;
}
.use-link-list li:nth-child(5) a::before {
    content: '\1F393'; 
    margin-right: 0.5rem;
}
.use-link-list li:nth-child(6) a::before {
    content: "\1F4DA"; 
    margin-right: 0.5rem;
}
</style>
    <?php endif ?>
</div>
