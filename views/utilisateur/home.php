<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

$title = "Accueil";
?>

<section class="presentation-section">
    <div class="mobil">
        <h2>Bienvenue sur GAENSAJ</h2>
        <p class="welcome-text">Votre nouvelle plateforme de gestion d'absence en ligne,on facilite le processus de gestion d'absence.</p>
    </div>
</section>