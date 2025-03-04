<?php
require '../vendor/autoload.php';
use App\Router;

$router = new Router(dirname(__DIR__) . '/views');
$router

    /**
     * Pour les utilisateurs de la plateforme
     */
    ->get('/', 'home/index', 'accueil')
    ->match('/login', 'login/login', 'login_page')
    ->match('/login/[*:slug]/[*:id]', 'compte/profil', 'login')
    ->get('/login/forget_password', 'Authentification/forget_password', 'forget_password')
    ->get('/login/forget_password/password_recorver/[*:slug]-[i:id]', 'Authentification/password_recorver', 'password_recover')

    /**
     * Pour l'administration uniquement 
     */
    ->get('/administration', 'admin/login', 'administration')
    ->run();
