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

    /* Pour servir les images de profil */
    ->get('/my/profil/[*:role]/[*:id]', 'proxy/photo', 'serve-photo')
    ->get('/my/profil/serve-file', 'proxy/presence', 'serve-presence')

    /* Lorsque l'utilisateur est connecté */
    ->get('/home/my/[*:role]', 'utilisateur/home','user-home')
    ->match('/my/[*:role]/dashboard', 'utilisateur/dashboard','user-dashboard')
    ->get('/my/profil/[*:role]','utilisateur/profil','user-profil')
    ->match('/edit/profil/[*:role]/[*:id]', 'utilisateur/editer/editerProfil', 'edit-profil')

    /* Contenu pour les profs */

    ->match('/my/use/professeur/liste-des-presence', 'utilisateur/professors/ressources/listePresence','professor-listePresence')
    ->match('/my/use/professeur/liste-des-presence/presence', 'utilisateur/professors/ressources/presence','professor-presence')
    ->match('/my/use/professeur/liste-des-etudants', 'utilisateur/professors/ressources/listeEtudiant','professor-listeEtudiant')
    ->match('/my/use/professeur/liste-des-presence/send', 'utilisateur/professors/ressources/addPresence', 'add-presence')
    ->match('/my/historics/professeur/absence', 'utilisateur/professors/historics/absence','historic-absence')
    
    /* Contenu pour les admins */
    ->match('/my/administration/justificatif','utilisateur/admin/justifyHistory','justification')

    ->match('/my/administration/modifier-prof','utilisateur/admin/gestionProf/modifierProf','modifier-professeur')
    ->match('/my/administration/ajouter-prof','utilisateur/admin/gestionProf/ajouterProf','ajouterProf')
    ->match('/my/administration/liste-des-professeurs', 'utilisateur/admin/gestionProf/listeProfesseur', 'liste-professeur')

    ->match('/my/administration/ajouter-Etudiant','utilisateur/admin/gestionEtudiant/ajouteretudiant','ajouter-etudiant')
    ->match('/my/administration/liste-Etudiants','utilisateur/admin/gestionEtudiant/listeEtudiants','liste-etudiants')
    ->match('/my/administration/modifier-student','utilisateur/admin/gestionEtudiant/modifierEtudiant','modifier-student')

    ->match('/my/administration/gestion/creneaux', 'utilisateur/admin/gestionCreneaux/listCreneau', 'gestion-creneau')

    ->match('/my/administration/gestion/liste-presence', 'utilisateur/admin/gestionListePresence/listePresence', 'liste-presence-soumis')
    ->match('/my/administration/gestion/liste-presence/voir-details', 'utilisateur/admin/gestionListePresence/supprimer', 'liste-presence-soumis-delete')
    ->match('/my/administration/gestion/liste-presence/delete', 'utilisateur/admin/gestionListePresence/voirDetails', 'liste-presence-soumis-details')

    ->match('/my/Liste-des-etudiants-privees-pour-examen','utilisateur/admin/absences/etudiantPrivee','etudiantprivee')
    ->match('/my/administration/historiques-des-absences','utilisateur/admin/absences/historiquesAbsences','historikAbscences')
    ->match('/my/administration/recapitulatif-Absences','utilisateur/admin/absences/recapAbsences','RecapAbsences')
    

    ->match(  '/my/administration/messagerie', 'utilisateur/admin/messagerie','admin-messagerie')
    
    // Contenu pour les étudiants
    ->match(  '/my/etudiant/messagerie', 'utilisateur/students/messagerie','etudiant-messagerie')
    ->match('/my/etudiant/ma_classe/[*:id]', 'utilisateur/students/listeEtudiantsClasse', 'liste-etudiant-classe')
    ->match('/my/etudiant/historique/absence', 'utilisateur/students/historique/absences','etudiant-absences')
    ->match('/my/etudiant/historique/justificatif', 'utilisateur/students/historique/justificatifs','etudiant-justificatifs')




    ->run();
    