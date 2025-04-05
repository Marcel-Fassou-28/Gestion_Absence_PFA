<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/dashbord/connected.css">
<<<<<<< HEAD
    <link rel="stylesheet" href="/css/profiles/test.css">
=======
    <link rel="stylesheet" href="/css/Profiles/test.css">
>>>>>>> 6eb4f5ab6cbb3e6c68f434e6e6547f1548ee1d4f
    <link rel="stylesheet" href="/css/dashbord-etudiant/dashbord-etudiant.css">
    <?php if(isset($_GET['use-link'])) {
        echo '<link rel="stylesheet" href="/css/use-link/presence.css">';
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
        <a href="<?= $urlUser['home'] ?>" class="nav-icon" aria-label="site icon & home page" aria-current="page">
            <span>GAENSAJ</span>
        </a>
        <div class="main-navlink">
            <ul class="navlink-container">
                <li><a href="<?= $urlUser['home'] ?>" aria-current="page">Accueil</a></li>
                <li><a href="<?= $urlUser['dashboard'] ?>">Dashboard</a></li>
            </ul>
            <div class="profil">
                <a class="profil-img">
                    <img  src="/images/profil.jpg" alt="profil">
                </a>
                <button class="show-menu" type="button">
                    <span></span>
                    <span></span>
                </button>
                <ul class="profil-pop-up">
                    <li><a href="<?= $urlUser['profil']?>">Profil</a></li>
                    <li><a href="">Calendrier</a></li>
                    <li><a href="">Historique</a></li>
                    <li><a href="">Signaler un problème</a></li>
                    <li></li>
                    <li><a href="<?= $router->url('page-deconnexion') ?>">Deconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <?= $content ?>
    </main>
    <footer>
    </footer>
    <script src="/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>