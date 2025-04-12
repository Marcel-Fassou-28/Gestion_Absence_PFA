
<div class="container dashboard">
    <div class="dashboard-intro">
        <h1>Tableau de Bord</h1>
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