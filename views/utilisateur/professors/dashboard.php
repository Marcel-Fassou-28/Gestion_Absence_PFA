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
                <li><a href="<?= $router->url('professor-listEtudiant') . '?use-link=student-list' ?>">Listes des Etudiants</a></li>
                <li><a href="<?= $router->url('professor-matiere') . '?use-link=my-subject' ?>">Autres Matières Enseignants</a></li>
            </ul>
        </section>
        <section class="container historic">
            <h2>Historiques</h2>
            <div class="hr"></div>
            <ul class="historic-list">
                <li><a href="<?= $router->url('historic-absence') . '?historic=absence' ?>">Historiques des Absences</a></li>
                <li><a href="<?= $router->url('historic-logs') . '?historic=logs' ?>">Historiques des Logs</a></li>
                <li><a href="<?= $router->url('historic-stats') . '?historic=stats' ?>">Statistiques</a></li>
            </ul>
        </section>
    </div>
</div>