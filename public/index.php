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

<<<<<<< HEAD
    ->get('/my/dashboard-etudiant','utilisateur/students/dashboard','dashboard')
    ->match('/logout','login/logout','deconnexion')

=======
    /* Contenu pour les profs */
    ->get('/my/use/calendrier', 'utilisateur/professors/ressources/calendrier','professor-calendrier')
    ->get('/my/use/liste-des-etudants', 'utilisateur/professors/ressources/listeEtudiant','professor-listEtudiant')
    ->get('/my/use/matieres-enseignes', 'utilisateur/professors/ressources/matiereEnseignes','professor-matiere')

    ->get('/my/historics/absence', 'utilisateur/professors/historics/absence','historic-absence')
    ->get('/my/historics/logs', 'utilisateur/professors/historics/logs','historic-logs')
    ->get('/my/historics/stats', 'utilisateur/professors/historics/stats','historic-stats')

    ->match('/my/logout', 'login/logout', 'page-deconnexion')
>>>>>>> 8c97789c2cd423440a95bf4f2ee7c93857466c35

    ->run();
