<?php
require '../vendor/autoload.php';
use App\Router;

$env = parse_ini_file(dirname(__DIR__) .DIRECTORY_SEPARATOR . '.env');
$secretKey = $env['SECRET_KEY'];

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'home/index', 'accueil')
    ->match('/login', 'login/login', 'page-connexion')
    ->match('/login/resetpassword', 'utilisateur/recovery/resetPassword', 'forget-password')
    ->match('/login/resetpassword/recover/[*:id]', 'utilisateur/recovery/passwordRecover', 'password-recovery')
    ->match('/login/recover/authentification/[*:id]', 'utilisateur/recovery/codeAuth', 'code-recuperation' )

    /* Lorsque l'utilisateur est connecté */
    ->get('/home/my/[*:role]/[*:id]', 'utilisateur/home','user-home')

    ->get('/my/professors/dashboard', 'utilisateur/professors/dashboard','professor-dashboard')
    ->get('/my/admin/dashboard', 'utilisateur/admin/dashboard','administrator-dashboard')
    ->get('/my/students/dashboard', 'utilisateur/students/dashboard','student-dashboard')

    ->get('/my/profil/[*:role]','utilisateur/profil','user-profil')

    /* Contenu pour les profs */
    ->match('/my/use/calendrier', 'utilisateur/professors/ressources/calendrier','professor-calendrier')
    ->match('/my/use/liste-des-presence', 'utilisateur/professors/ressources/listePresence','professor-listePresence')
    ->match('/my/use/liste-des-presence/presence', 'utilisateur/professors/ressources/presence','professor-presence')
    ->match('/my/use/liste-des-etudants', 'utilisateur/professors/ressources/listeEtudiant','professor-listeEtudiant')
    ->match('/my/use/matieres-enseignes', 'utilisateur/professors/ressources/autreInfo','professor-autreInfo')

    ->match('/my/historics/absence', 'utilisateur/professors/historics/absence','historic-absence')
    ->match('/my/historics/stats', 'utilisateur/professors/historics/stats','historic-stats')

    ->match('/my/logout', 'login/logout', 'page-deconnexion')
    /* Contenu pour les admins */
    ->match('/my/liste-des-professeurs', 'utilisateur/admin/listeProfesseur','liste_Des_Professeur')
    ->match('/my/justificatif','utilisateur/admin/justifyHistory','justification')
    ->match('/my/modifier-prof','utilisateur/admin/modifierProf','modifier_professeur')
    ->match('/my/ajouter-prof','utilisateur/admin/ajouterProf','ajouterProf')

    
    ->match('/api/matiere_classe', 'api/get_matiere_classe', 'api')
    ->match(  '/my/admin/messagerie', 'utilisateur/admin/messagerie','admin-messagerie')
    
    // Contenu pour les étudiants
    ->match(  '/my/etudiant/messagerie', 'utilisateur/etudiant/messagerie','etudiant-messagerie')
    ->match('/my/etudiant/historique/absence', 'utilisateur/etudiant/historique/absences','etudiant-absences')




    ->run();
    