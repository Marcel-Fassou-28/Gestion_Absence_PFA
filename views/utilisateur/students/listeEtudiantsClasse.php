<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'etudiant') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}
