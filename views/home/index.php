<?php
if(isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

?>

<section class="presentation-section">
    <div class="mobil">
        <h2>Bienvenue sur GAENSAJ</h2>
        <p class="welcome-text">Votre nouvelle plateforme de gestion d'absence en ligne</p>
        <button><a href="<?= $router->url('page-connexion') ?>" target="_blank">Connectez-vous</a></button>
    </div>
</section>