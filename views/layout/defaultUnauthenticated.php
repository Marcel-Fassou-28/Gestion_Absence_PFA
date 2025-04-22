<?php
if(isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

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
    <link rel="stylesheet" href="/css/main-style.css">
    <title>Gestion d'Absence</title>
</head>
<body>
    <nav>
        <a href="" class="nav-icon" aria-label="site icon & home page" aria-current="page">
            <span>GAENSAJ</span>
        </a>
        <div class="main-navlink">
            <button class="menu-hamburger" type="button" aria-label="afficher les liens de navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="navlink-container">
                <li><a href="<?= $router->url('accueil') ?>" aria-current="page">Accueil</a></li>
                <li><a href="<?= $router->url('page-connexion') ?>">Connexion</a></li>
            </ul>
        </div>
    </nav>
    <main>
        <?= $content ?>
    </main>
    <footer class="footer">
        <div class="left-side"></div>
        <div class="footer-container">
        <div class="footer-brand">
        <h2>GAENSAJ</h2>
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
    <script src="/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>