<?php 

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

use App\Connection;
use App\UserTable;

$pdo = Connection::getPDO();
$cin = $_SESSION['id_user'];
$tableUser = new UserTable($pdo);
$user = $tableUser->getIdentification($cin);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/css/dashbord/connected.css">
    
    <link rel="stylesheet" href="/css/dashboard-etudiant/dashboard-etudiant.css">

    <?php if (isset($_GET['about'])) {
        echo '<link rel="stylesheet" href="/css/About/about.css">';
    }?>

    <?php if(isset($_GET['use-link'])) {
        echo '<link rel="stylesheet" href="/css/use-link/presence.css">';
    }
    if (isset($_GET['modifier'])){
        echo '<link rel="stylesheet" href="/css/list_prof/modifierProf.css">';
    }
    if (isset($_GET['matiere']) || isset($_GET['admin']) || isset($_GET['classe'])) {
        echo '<link rel="stylesheet" href="/css/matiere/list-matiere.css">';
        echo '<link rel="stylesheet" href="/css/matiere/modifie-matiere.css">';
    }
    if (isset($_GET['listprof'])) {
        echo '<link rel="stylesheet" href="/css/list_prof/list.css">';
        echo '<link rel="stylesheet" href="/css/modifie/modifie.css">';
        if (isset($_GET['justifier'])) {
            echo '<link rel="stylesheet" href="/css/justificatif/justificatif.css">';
        }
    }
    if (isset($_GET['historic'])) {
        echo '<link rel="stylesheet" href="/css/use-link/historic.css">';
    }
    if(isset($_GET['user'])) {
        echo '<link rel="stylesheet" href="/css/profil/profil.css">';
    }
    if(isset($_GET['edit_profil'])) {
        echo '<link rel="stylesheet" href="/css/profil/editerProfil.css">';
    }
    if(isset($_GET['messagerie'])){
        echo '<link rel="stylesheet" href="/css/dashboard-etudiant/dashboard-etudiant.css">';
    }
    ?>
    <title>Gestion d'Absence</title>
</head>
<body> 
    <nav>
        <button class="menu-hamburger" type="button" aria-label="afficher les liens de navigation" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <a href="<?= $router->url('user-home', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]) ?>" class="nav-icon" aria-label="site icon & home page" aria-current="page">
            <span>GAENSAJ</span>
        </a>
        <div class="main-navlink">
            <ul class="navlink-container">
                <li><a href="<?= $router->url('user-home', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]) ?>" aria-current="page">Accueil</a></li>
                <li><a href="<?= $router->url('user-dashboard',  ['role'=> $_SESSION['role']]) ?>">Dashboard</a></li>
            </ul>
            <div class="profil">
                <a class="profil-img">
                    <img  src="<?= $router->url('serve-photo', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]) ?>" alt="profil">
                </a>
                <button class="show-menu" type="button">
                    <span></span>
                    <span></span>
                </button>
                <ul class="profil-pop-up">
                    <li><a href="<?= $router->url('user-profil', ['role'=> $_SESSION['role']]) . '?user='.$_SESSION['role']?>">Profil</a></li>

                <?php if ($_SESSION['role'] === 'etudiant'): ?>
                        <li><a href="<?= $router->url('etudiant-absences').'?listprof=1'?>">Mes Absences</a></li>
                        <li><a href="<?= $router->url('etudiant-messagerie').'?listprof=1' ?>">Messagerie</a></li>
                    <?php elseif($_SESSION['role'] === 'admin'): ?>
                        <li><a href="<?= $router->url('historikAbscences') .'?listprof=1&p=0' ?>">Absences des Etudiants</a></li>
                        <li><a href="<?= $router->url('admin-messagerie').'?messagerie=1&listprof=1' ?>">Messagerie</a></li>

                    <?php else: ?>
                        <li><a href="<?= $router->url('historic-absence') . '?historic=absence&p=0' ?>">Historique des Absences</a></li>
                        <li><a href="<?= $router->url('professor-listeEtudiant') . '?use-link=student-list&p=0' ?>">Liste des Etudiants</a></li>

                <?php endif ?>

                    <li><a href="<?= $router->url('signaler_probleme', ['id' => $_SESSION['id_user']]) . '?classe=1' ?>">Signaler un problème</a></li>
                    <li></li>
                    <li><a href="<?= $router->url('page-deconnexion') ?>">Deconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <?= $content ?>
    </main>
    <footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <a href="<?= $router->url('accueil') ?>" class="footer-logo" aria-label="Retour à l'accueil">
                <!-- Placeholder pour le logo, remplacez src par votre image -->
                
                <h2 class="footer-title">GAENSAJ</h2>
            </a>
            <p>Plateforme de gestion d'absences GAENSAJ</p>
        </div>
        <div class="footer-links">
            <h3 class="footer-section-title">Liens utiles</h3>
            <ul>
                <li><a href="<?= $router->url('user-home', ['role' => $_SESSION['role'], 'id' => $_SESSION['id_user']]) ?>" aria-label="Accueil">Accueil</a></li>
                <li><a href="<?= $router->url('about') . '?about=1' ?>" aria-label="À propos">À propos</a></li>
            </ul>
        </div>
        <div class="footer-contact">
            <h3 class="footer-section-title">Contact</h3>
            <ul>
                <li><a href="mailto:support@gaensaj.com" aria-label="Envoyer un email">support@gaensaj.com</a></li>
                <li><a href="tel:+212123456789" aria-label="Appeler le support">+212 123 456 789</a></li>
                <li>ENSAJ, El Jadida, Maroc</li>
            </ul>
        </div>
        <div class="footer-social">
            <h3 class="footer-section-title">Suivez-nous</h3>
            <div class="social-icons">
                <a href="#" aria-label="LinkedIn ENSAJ" class="social-icon"><i class="fab fa-linkedin"></i></a>
                <a href="#" aria-label="Twitter ENSAJ" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram ENSAJ" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-rights">
            <p>© 2025 GAENSAJ - Tous droits réservés. <a href="#" aria-label="Politique de confidentialité">Politique de confidentialité</a></p>
        </div>
    </div>
</footer>
    <script src="/js/scriptConnected.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>