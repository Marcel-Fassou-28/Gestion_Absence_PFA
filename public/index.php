<?php
require '../vendor/autoload.php';
use App\Router;

$env = parse_ini_file(dirname(__DIR__) .DIRECTORY_SEPARATOR . '.env');
$secretKey = $env['SECRET_KEY'];

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'home/index', 'accueil')
    ->match('/login', 'login/login', 'page-connexion')
    ->match('/my/logout', 'login/logout', 'page-deconnexion')
    ->match('/login/resetpassword', 'utilisateur/recovery/resetPassword', 'forget-password')
    ->match('/login/resetpassword/recover/[*:id]', 'utilisateur/recovery/passwordRecover', 'password-recovery')
    ->match('/login/recover/authentification/[*:id]', 'utilisateur/recovery/codeAuth', 'code-recuperation' )

    /* Lorsque l'utilisateur est connecté */
    ->get('/home/my/[*:role]', 'utilisateur/home','user-home')

    ->get('/my/admin/dashboard', 'utilisateur/admin/dashboard','administrator-dashboard')

    ->get('/my/[*:role]/dashboard', 'utilisateur/dashboard','user-dashboard')
    ->get('/my/profil/[*:role]','utilisateur/profil','user-profil')
    ->match('/edit/profil/[*:role]/[*:id]', 'utilisateur/editer/editerProfil', 'edit-profil')

    /* Contenu pour les profs */

    ->match('/my/use/professeur/liste-des-presence', 'utilisateur/professors/ressources/listePresence','professor-listePresence')
    ->match('/my/use/professeur/liste-des-presence/presence', 'utilisateur/professors/ressources/presence','professor-presence')
    ->match('/my/use/professeur/liste-des-etudants', 'utilisateur/professors/ressources/listeEtudiant','professor-listeEtudiant')
    ->match('/my/use/professeur/liste-des-presence/send', 'utilisateur/professors/ressources/addPresence', 'add-presence')
    ->match('/my/historics/professeur/absence', 'utilisateur/professors/historics/absence','historic-absence')
    /* Contenu pour les admins */
    ->match('/my/liste-des-professeurs', 'utilisateur/admin/gestionProf/listeProfesseur',name: 'liste_Des_Professeur')
    ->match('/my/justificatif','utilisateur/admin/justifyHistory','justification')
    ->match('/my/modifier-prof','utilisateur/admin/gestionProf/modifierProf','modifier_professeur')
    ->match('/my/ajouter-prof','utilisateur/admin/gestionProf/ajouterProf','ajouterProf')
    ->match('/my/ajouter-Etudiant','utilisateur/admin/gestionEtudiant/ajouteretudiant','ajouterEtudiant')
    ->match('/my/liste-Etudiants','utilisateur/admin/gestionEtudiant/listeEtudiants','liste_Des_etudiants')
    ->match('/my/modifier-student','utilisateur/admin/gestionEtudiant/modifierEtudiant','modifier-student')
    ->match('/my/historiques-des-absences','utilisateur/admin/absences\historiquesAbsences','historikAbscences')
    ->match('/my/recapitulatif-Absences','utilisateur/admin/absences/recapAbsences','RecapAbsences')
    
    ->match('/api/matiere_classe', 'api/get_matiere_classe', 'api')
    ->match('/my/modifier-prof','utilisateur/admin/modifierProf','modifier_professeur')
    ->match('/my/ajouter-prof','utilisateur/admin/ajouterProf','ajouterProf')

    ->match(  '/my/admin/messagerie', 'utilisateur/admin/messagerie','admin-messagerie')
    
    // Contenu pour les étudiants
    ->match(  '/my/etudiant/messagerie', 'utilisateur/students/messagerie','etudiant-messagerie')
    ->match('/my/etudiant/liste_etudiant_classe/[*:id]', 'utilisateur/students/listeEtudiantClasse', 'liste-etudiant-classe')
    ->match('/my/etudiant/historique/absence', 'utilisateur/students/historique/absences','etudiant-absences')




    ->run();
    