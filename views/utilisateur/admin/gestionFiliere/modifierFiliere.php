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
use App\Connection;
use App\Logger;
use App\Model\Administrateur;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$modifie_sucess = null;
$super_admin = null;

$idFiliere = $_GET['id'];
if (isset($idFiliere)) {
    $filiere = $result->getFieldsById($idFiliere);
    $Departement = $result->getDepartementById($filiere[0]->getIDDepartement());
    $oldfiliere = $filiere[0]->getNomFiliere();
    $nomDepartement = $Departement[0]->getNomDepartement();
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newfiliere = $_POST['nomFiliere'];
        $alias = $_POST['alias'];
        $idDepartement =  $result->getIdDepartementByName( $_POST['Nom_departement']);

        $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
        $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
        $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
        $admin_verifie = $query_verifie->fetch();
     
        if ($admin_verifie && $admin_verifie->getIDAdmin() == '1') {

            if ($result->modifierFiliere($idFiliere, $newfiliere, $oldfiliere, $alias, $idDepartement)) {
                $modifie_sucess = 1;
                Logger::log("Mofication d'une filiere", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
            } else {
                $modifie_sucess = 0;
            }
            header('location: '.$router->url('liste-filiere-admin').'?listprof=1&p=0&modifie_success='.$modifie_sucess);
            exit();
        } else {
            $super_admin = 1;
            header('location:' . $router->url('liste-filiere-admin'). '?listprof=1&p=0&super_admin='.$super_admin);
            exit();
        }
} else {

}

} else {
    /*header('location: '.$router->url('liste-filiere-admin').'?listprof=1&p=0&modifie_success='.$modifie_sucess);
    exit();*/
}

?>

<div class="prof-list">
    <div class="intro-prof-list">
        <h1>Modifier les Informations de la filiere</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div>
                    <label for="username">Nom Filiere</label>
                    <input type="text" name="nomFiliere" value="<?= htmlspecialchars($filiere[0]->getNomFiliere()) ?>">
                </div>
                <div>
                    <label for="cin">Alias</label>
                    <input type="text" name="alias" value="<?= htmlspecialchars($filiere[0]->getAlias()) ?>">
                </div>
                <div>
                    <label for="nom">Nom departement</label>
                    <input type="text" name="Nom_departement" value="<?= htmlspecialchars($Departement[0]->getNomDepartement()) ?>">
                </div>
                
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Modifier</button>
                <a class="btn2" href="<?= $router->url('liste-filiere-admin').'?listprof=1&p=0' ?>">Annuler</a>
            </section>

        </form>
    </div>
</div>