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
    <link rel="stylesheet" href="/css/dashbord/connected.css">
    
    <link rel="stylesheet" href="/css/dashboard-etudiant/dashboard-etudiant.css">

 

    <?php if(isset($_GET['use-link'])) {
        echo '<link rel="stylesheet" href="/css/use-link/presence.css">';
    }
    if (isset($_GET['modifier'])){
        echo '<link rel="stylesheet" href="/css/list_prof/modifierProf.css">';
    }
    if (isset($_GET['listprof'])) {
        echo '<link rel="stylesheet" href="/css/list_prof/list.css">';
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
                        <li><a href="<?= $router->url('etudiant-absences')?>">Mes Absences</a></li>
                        <li><a href="<?= $router->url('etudiant-messagerie') ?>">Messagerie</a></li>
                    <?php elseif($_SESSION['role'] === 'admin'): ?>
                        <li><a href="<?= $router->url('historikAbscences') ?>">Absences des Etudiants</a></li>
                        <li><a href="<?= $router->url('admin-messagerie') ?>">Messagerie</a></li>

                    <?php else: ?>
                        <li><a href="<?= $router->url('historic-absence') . '?historic=absence' ?>">Historique des Absences</a></li>
                        <li><a href="<?= $router->url('professor-listeEtudiant') . '?use-link=student-list' ?>">Liste des Etudiants</a></li>

                <?php endif ?>

                    <li><a href="#">Signaler un problème</a></li>
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
        <div class="left-side"></div>
        
        <div class="footer-container">
        <div class="footer-brand">
        <h2 class="footer-title">GAENSAJ</h2>
        <p>Plateforme de gestion d'absence de l'ENSAJ</p>
        </div>
        <div class="footer-links">
        <a href="<?= $router->url('user-home', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]) ?>">Accueil</a>
      <a href="#">À propos</a>
      <a href="#">Contact</a>
      <a href="#">Support</a>
        </div>
        <div class="footer-rights">
      <p>&copy; 2025 GAENSAJ - Tous droits réservés.</p>
        </div>
    </div>

        <div class="right-side"></div>
    </footer>
    <script src="/js/scriptConnected.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>