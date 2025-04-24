<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}
?>

<section class="presentation-section">
    <div class="mobil">
        <h2>Bienvenue sur GAENSAJ</h2>
        <p class="welcome-text">Votre nouvelle plateforme de gestion d'absence en ligne, faciliter le processus de gestion d'absence est notre priorité.</p>
    </div>
</section>