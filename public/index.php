<?php
require '../vendor/autoload.php';
use App\Router;

$env = parse_ini_file(dirname(__DIR__) .DIRECTORY_SEPARATOR . '.env');
$secretKey = $env['SECRET_KEY'];

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'home/index', 'accueil')
    ->get('/about', 'About/about', 'about')
    ->match('/verification', 'utilisateur/verify_by_email', 'verify-email')
    ->match('/login', 'login/login', 'page-connexion')
    ->match('/my/logout', 'login/logout', 'page-deconnexion')
    ->match('/login/resetpassword', 'utilisateur/recovery/resetPassword', 'forget-password')
    ->match('/login/resetpassword/recover/[*:id]', 'utilisateur/recovery/passwordRecover', 'password-recovery')
    ->match('/login/recover/authentification/[*:id]', 'utilisateur/recovery/codeAuth', 'code-recuperation' )
    ->match('/user/signaler_probleme/[*:id]', 'utilisateur/signaler_probleme', 'signaler_probleme')

    /* Pour servir les images de profil */
    ->get('/my/profil/[*:role]/[*:id]', 'proxy/photo', 'serve-photo')
    ->get('/my/profil/serve-file', 'proxy/presence', 'serve-presence')
    ->get('/my/justificatif/serve-justificatif', 'proxy/justificatif', 'serve-justificatif')

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
    ->match('/my/administration/justificatif','utilisateur/admin/gestionJustificatif/justifyHistory','justification')
    ->match('/my/administration/Traiter_justificatif','utilisateur/admin/gestionJustificatif/traiterJustificatif','traitement_justificatif')
    ->match('/my/administration/Detail_justificatif','utilisateur/admin/gestionJustificatif/detailJustificatif','detail_justificatif')
    ->match('/my/administration/Rejeter_justificatif','utilisateur/admin/gestionJustificatif/rejecterJustificatif','rejecter_justificatif')
    ->match('/my/administration/Supprimer_justificatif','utilisateur/admin/gestionJustificatif/supprimerJustificatif','supprimer_justificatif')


    ->match('/my/administration/modifier-prof','utilisateur/admin/gestionProf/modifierProf','modifier-professeur')
    ->match('/my/administration/supprimer-prof','utilisateur/admin/gestionProf/supprimerProf','supprimer-professeur')
    ->match('/my/administration/ajouter-prof','utilisateur/admin/gestionProf/ajouterProf','ajouterProf')
    ->match('/my/administration/liste-des-professeurs', 'utilisateur/admin/gestionProf/listeProfesseur', 'liste-professeur')

    ->match('/my/administration/ajouter-Etudiant','utilisateur/admin/gestionEtudiant/ajouterEtudiant','ajouter-etudiant')
    ->match('/my/administration/liste-Etudiants','utilisateur/admin/gestionEtudiant/listeEtudiants','liste-etudiants')
    ->match('/my/administration/modifier-student','utilisateur/admin/gestionEtudiant/modifierEtudiant','modifier-student')
    ->match('/my/administration/supprimer-etudiant','utilisateur/admin/gestionEtudiant/supprimerEtudiant','supprimer-student')

    ->match('/my/administration/gestion/creneaux', 'utilisateur/admin/gestionCreneaux/listCreneau', 'gestion-creneau')

    ->match('/my/administration/gestion/liste-presence', 'utilisateur/admin/gestionListePresence/listePresence', 'liste-presence-soumis')
    ->match('/my/administration/gestion/ajouter-absence-liste', 'utilisateur/admin/gestionListePresence/ajouterAbsence', 'ajouter-considerer')
    ->match('/my/administration/gestion/liste-presence/voir-details', 'utilisateur/admin/gestionListePresence/supprimer', 'liste-presence-soumis-delete')
    ->match('/my/administration/gestion/liste-presence/delete', 'utilisateur/admin/gestionListePresence/voirDetails', 'liste-presence-soumis-details')

    ->match('/my/Liste-des-etudiants-privees-pour-examen','utilisateur/admin/absences/etudiantPrivee','etudiantprivee')
    ->match('/my/administration/historiques-des-absences','utilisateur/admin/absences/historiquesAbsences','historikAbscences')
    ->match('/my/administration/recapitulatif-Absences','utilisateur/admin/absences/recapAbsences','RecapAbsences')
    

    ->match(  '/my/administration/messagerie', 'utilisateur/admin/messagerie','admin-messagerie')

    /* Gestion des creneaux */
    ->match('/my/administration/gestion_creneaux/delete', 'utilisateur/admin/gestionCreneaux/supprimerCreneaux', 'supprimer-creneaux')
    ->match('/my/administration/gestion_creneaux/ajouter', 'utilisateur/admin/gestionCreneaux/ajouterCreneaux', 'ajouter-creneaux')
    ->match('/my/administration/gestion_creneaux/modifier', 'utilisateur/admin/gestionCreneaux/modifierCreneau', 'modifier-creneaux')
    
    // Contenu pour les étudiants
    ->match(  '/my/etudiant/messagerie', 'utilisateur/students/messagerie','etudiant-messagerie')
    ->match('/my/etudiant/ma_classe', 'utilisateur/students/listeEtudiantsClasse', 'liste-etudiant-classe')
    ->match('/my/etudiant/historique/absence', 'utilisateur/students/historique/absences','etudiant-absences')
    ->match('/my/etudiant/historique/justificatif', 'utilisateur/students/historique/justificatifs','etudiant-justificatifs')


    /* gestion des matieres... */
    ->match('/my/administration/gestion-matiere', 'utilisateur/admin/gestionCours/listeMatiere', 'liste-matiere-admin')
    ->match('/my/administration/gestion-matiere/supprimer', 'utilisateur/admin/gestionCours/supprimerMatiere', 'liste-matiere-delete')
    ->match('/my/administration/gestion-matiere/modifie', 'utilisateur/admin/gestionCours/modifierMatiere', 'liste-matiere-modifie')
    ->match('/my/administration/gestion-matiere/ajouter', 'utilisateur/admin/gestionCours/ajouterMatiere', 'liste-matiere-ajouter')
  
    /* gestion des filiere... */
    ->match('/my/administration/gestion-filiere', 'utilisateur/admin/gestionFiliere/listeFiliere', 'liste-filiere-admin')
    ->match('/my/administration/gestion-filiere/supprimer', 'utilisateur/admin/gestionFiliere/supprimerFiliere', 'liste-filiere-delete')
    ->match('/my/administration/gestion-filiere/modifie', 'utilisateur/admin/gestionFiliere/modifierFiliere', 'liste-filiere-modifie')
    ->match('/my/administration/gestion-filiere/ajouter', 'utilisateur/admin/gestionFiliere/ajouterFiliere', 'liste-filiere-ajouter')
    

    /* gestion des administrateur */
    ->match('/my/administration/gestion-admin', 'utilisateur/admin/gestionAdmin/listeAdmin', 'liste-des-admin')
    ->match('/my/administration/gestion-admin/supprimer', 'utilisateur/admin/gestionAdmin/supprimerAdmin', 'admin-delete')
    ->match('/my/administration/gestion-admin/modifier', 'utilisateur/admin/gestionAdmin/modifierAdmin', 'admin-modifie')
    ->match('/my/administration/gestion-admin/ajouter', 'utilisateur/admin/gestionAdmin/ajouterAdmin', 'admin-ajouter')

    /* gestion des administrateur */
    ->match('/my/administration/gestion-classe', 'utilisateur/admin/gestionClasses/listeClasse', 'gestion-classe')
    ->match('/my/administration/gestion-classe/supprimer', 'utilisateur/admin/gestionClasses/supprimerClasse', 'classe-delete')
    ->match('/my/administration/gestion-classe/modifie', 'utilisateur/admin/gestionClasses/modifierClasse', 'classe-modifie')


    ->match('/my/export_pdf','utilisateur/admin/absences/export_pdf','exportPdf')

    /* API */
    ->match('/my/api/liste-fichier-presence', 'API/API_fichier_liste_presence', 'api-liste-classe')
    ->match('/my/api/liste-creneaux', 'API/API_liste_creneaux', 'api-liste-filiere')
    ->match('/my/api/liste-professeur', 'API/API_liste_pour_prof', 'api-liste-departement')
    ->match('/my/api/professeur/select-classe-matiere', 'API/Professeur/API_list_absence', 'api-prof-liste-clm')
    ->match('/my/api/professeur/select-classe-etudiant', 'API/Professeur/API_list_etudiant', 'api-prof-liste-etud')
    ->match('/my/api-admin/select-niveau-filiere', 'API/Admin/API_fichier_liste_classe', 'api-admin-niveau-filiere')

    ->run();
    