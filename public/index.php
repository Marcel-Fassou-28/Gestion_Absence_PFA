<?php
require '../vendor/autoload.php';
use App\Router;

$env = parse_ini_file(dirname(__DIR__) .DIRECTORY_SEPARATOR . '.env');
$secretKey = $env['SECRET_KEY'];

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'home/index', 'accueil')
    ->match('/login', 'login/login', 'page-connexion')
    ->get('/login/reset-password', 'utilisateur/recovery/passwordRecover', 'forget-password')
    ->get('/login/reset-password/recover/[*:id]', 'utilisateur/recovery/resetPassword', 'password-recovery')
    ->match('/login/[*:id]/my/[*:role]', 'utilisateur/redirect', 'authenticated')
    
    /*Redirect de l'Utilisateur */
    ->get('/[*:name]/[*:id]', 'utilisateur/professors/home','professor')
    ->get('/[*:name]/[*:id]', 'utilisateur/admin/home','administrator')
    ->get('/[*:name]/[*:id]', 'utilisateur/students/home','student')
    
    /* Lorsque l'utilisateur est connecté */

    ->run();
