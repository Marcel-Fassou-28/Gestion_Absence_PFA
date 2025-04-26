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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                <li><a href="<?= $router->url('accueil') ?>" aria-label="Accueil">Accueil</a></li>
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
                <a href="#" aria-label="LinkedIn GAENSAJ" class="social-icon"><i class="fab fa-linkedin"></i></a>
                <a href="#" aria-label="Twitter GAENSAJ" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram GAENSAJ" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-rights">
            <p>© 2025 GAENSAJ - Tous droits réservés. <a href="#" aria-label="Politique de confidentialité">Politique de confidentialité</a></p>
        </div>
    </div>
</footer>
    <script src="/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>