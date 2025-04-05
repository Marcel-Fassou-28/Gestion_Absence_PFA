<?php
require '../vendor/autoload.php';
use App\Router;

$env = parse_ini_file(dirname(__DIR__) .DIRECTORY_SEPARATOR . '.env');
$secretKey = $env['SECRET_KEY'];

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'home/index', 'accueil')
    ->match('/login', 'login/login', 'page-connexion')
    ->get('/login/reset-password', 'utilisateur/recovery/resetPassword', 'forget-password')
    ->get('/login/reset-password/recover/[*:id]', 'utilisateur/recovery/passwordRecover', 'password-recovery')
    ->match('/login/[*:id]/my/[*:role]', 'utilisateur/redirect', 'authenticated')

    /* Lorsque l'utilisateur est connecté */
    ->get('/my', 'utilisateur/professors/home','professor-home')
    ->get('/my', 'utilisateur/admin/home','administrator-home')
    ->get('/my', 'utilisateur/students/home','student-home')

    ->get('/my/dashboard', 'utilisateur/professors/dashboard','professor-dashboard')
    ->get('/my/dashboard', 'utilisateur/admin/dashboard','administrator-dashboard')
    ->get('/my/dashboard', 'utilisateur/students/dashboard','student-dashboard')
    ->get('/my/profil','utilisateur/profile','admin-profil')
    ->match('/my/profil', 'utilisateur/profile',)
    ->run();
    