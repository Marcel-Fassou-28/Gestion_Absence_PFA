<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

session_unset();
session_destroy();
header('Location: ' . $router->url('accueil'));
exit();
