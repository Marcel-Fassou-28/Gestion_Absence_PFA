<?php

if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Admin\adminTable;
use App\connection;
use App\Logger;
use App\Model\Administrateur;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$pdo = Connection::getPDO();
$result = new adminTable($pdo);
 // chercher l'id de la dernier filiere afin de creer les classe
$filiere = $result->getAll('filiere', 'classFiliere');
foreach ($filiere as $field)
    $id = $field->getIDFiliere();

$success = 1;
if (!empty($_POST)) {
    $nomfiliere = $_POST['nomfiliere'];
    $alias = $_POST['alias'];
    $nomDepartement = $_POST['depart'];
    $nbreAnneeEtude = $_POST['Annee_etude'];
    $idDepartement = $result->getIdDepartementByName($nomDepartement);
    $idFiliere = $result->getIdFiliereByName($nomfiliere);

    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();
     
    if ($admin_verifie && $admin_verifie->getIDAdmin() == '1') {
            //on verifie si le departement existe et le filiere n'existe pas deja 
        if ($idDepartement === null || $idFiliere !== null) {
            $success = 0;
        }

        if ($success === 1) {
            $result->addFiliere($id + 1, $nomfiliere, $alias, $idDepartement, $nbreAnneeEtude);
            Logger::log("Ajout d'une filiere", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
        }

        header('Location: ' . $router->url('liste-filiere-admin') . '?listprof=1&p=0&success_filiere=' . $success);
        exit();
    } else {
        $super_admin = 1;
        header('location:' . $router->url('liste-filiere-admin'). '?listprof=1&p=0&super_admin='.$super_admin);
        exit();
    }

    
    /**/
}
?>


<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Ajouter une filiere</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div>
                    <label for="filiere">Nom filiere</label>
                    <input type="text" name="nomfiliere" value="" required>
                </div>
                <div>
                    <label for="nom">Alias filiere</label>
                    <input type="text" name="alias" value="" required>
                </div>
                <div>
                    <label for="prenom">nom Departement</label>
                    <input type="text" name="depart" value="" required>
                </div>
                <div>
                    <label for="Annee_etude">Nombre d'annee d'etude</label>
                    <select class="select-nbr-annee" name="Annee_etude" id="">
                        <option name="Annee_etude" value="2">2 Années</option>
                        <option name="Annee_etude" value="3" selected>3 Années</option>
                    </select>
                    <!--<input type="number" name="Annee_etude" value="" min=2 max=3 required>-->
                </div>

            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Ajouter</button>
                <button class="btn2"
                    onclick="window.location.href='<?= $router->url('liste-filiere-admin') . '?listprof=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>