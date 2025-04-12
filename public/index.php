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
    ->get('/my/profil','utilisateur/profil','admin-profil')
    ->get('/my/profil','utilisateur/profil','professor-profil')
    ->get('/my/profil','utilisateur/profil','etudiant-profil')

    ->get('/my/dashboard-etudiant','utilisateur/students/dashboard','dashboard')

    /* Contenu pour les profs */
    ->match('/my/use/calendrier', 'utilisateur/professors/ressources/calendrier','professor-calendrier')
    ->match('/my/use/liste-des-presence', 'utilisateur/professors/ressources/listePresence','professor-listePresence')
    ->match('/my/use/liste-des-etudants', 'utilisateur/professors/ressources/listeEtudiant','professor-listeEtudiant')
    ->match('/my/use/matieres-enseignes', 'utilisateur/professors/ressources/autreInfo','professor-autreInfo')

    ->match('/my/historics/absence', 'utilisateur/professors/historics/absence','historic-absence')
    ->match('/my/historics/stats', 'utilisateur/professors/historics/stats','historic-stats')

    ->match('/my/logout', 'login/logout', 'page-deconnexion')
    /* Contenu pour les admins */
    ->match('/my/liste-des-professeurs', 'utilisateur/admin/listeProfesseur','liste_Des_Professeur')
    
    ->run();
    