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

$pdo = Connection::getPDO();
$result = new adminTable($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$modifie_sucess = null;

$cinProf = $_GET['cin'];
if (isset($cinProf)) {
    $prof = $result->getProfesseurByCIN($cinProf);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $cinProf = $_POST['cin'];
        $nomProf = $_POST['nom'];
        $prenomProf = $_POST['prenom'];
        $emailProf = $_POST['email'];
        $oldCinProf = $prof->getCIN();

        if ($result->ModifierProf($username, $cinProf, $nomProf, $prenomProf, $emailProf, $oldCinProf)) {
            $modifie_sucess = 1;
            Logger::log("Modification des infos d'un prof", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
        } else {
            $modifie_sucess = 0;
        }
        header('location: '.$router->url('liste-professeur').'?listprof=1&p=0&modifie_success='.$modifie_sucess);
        exit();
    }
} else {
    header('location: '.$router->url('liste-professeur').'?listprof=1&p=0&modifie_success='.$modifie_sucess);
    exit();
}

?>

<div class="prof-list">
    <div class="intro-prof-list">
        <h1>Modifier les Informations d'un professeur</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($prof->getUsername()) ?>">
                </div>
                <div>
                    <label for="cin">CIN</label>
                    <input type="text" name="cin" value="<?= htmlspecialchars($prof->getCIN()) ?>">
                </div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($prof->getNom()) ?>">
                </div>
                <div>
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($prof->getPrenom()) ?>">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" name="email" value="<?= htmlspecialchars($prof->getEmail()) ?>">
                </div>
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Modifier</button>
                <a class="btn2" href="<?= $router->url('liste-professeur').'?listprof=1&p=0' ?>">Annuler</a>
            </section>

        </form>
    </div>
</div>