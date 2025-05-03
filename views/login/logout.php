<?php

use App\Logger;

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

Logger::log("Deconnexion", 1, "SECURITY", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);

session_unset();
session_destroy();
header('Location: ' . $router->url('accueil'));
exit();
