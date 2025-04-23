<?php

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Admin\adminTable;
use App\connection;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$success_prof = null;

if (!empty($_POST)) {
    $role = 'professeur';
    $cinProf = $_POST['cin'];
    $nomProf = $_POST['nom'];
    $prenomProf = $_POST['prenom'];
    $emailProf = $_POST['email'];
    $username = $cinProf . '.' . $nomProf;
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($result->AddProfUser($cinProf, $nomProf, $prenomProf, $emailProf, $username, $password, $role)) {
        $success_prof = 1;
    } else {
        $success_prof = 0;
    }

    header('Location: ' . $router->url('liste-professeur').'?listprof=1&p=0&success_prof='. $success_prof );
    exit();

}
?>


<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Ajouter un professeur</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div class="creneau-debut">
                    <label for="cin">CIN</label>
                    <input type="text" name="cin" value="" required>
                </div>
                <div class="creneau-fin">
                    <label for="nom">Nom</label>
                <input type="text" name="nom" value="" required>
                </div>
                <div class="joursemaine">
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" value="" required>
                </div>
                <div class="matiere">
                    <label for="email">Email</label>
                    <input type="text" name="email" value="" required>
                </div>
                <div class="nomprof">
                    <label for="password">Password par Defaut</label>
                    <input type="text" name="password" value="" required>
                </div>
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Ajouter</button>
                <button class="btn2" onclick= "window.location.href='<?= $router->url('liste-professeur').'?listprof=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>
<!--
<div class="contains">
<div class="modif">
    <h2>Entrer les information puis valider</h2>
    <div class="modif-div">
        <form method="POST" class="div-form" action="">
            <div class="form-input">
                <label for="cin">CIN</label>
                <input type="text" name="cin" value="" required>
            </div>
            <div class="form-input">
                <label for="nom">Nom</label>
                <input type="text" name="nom" value="" required>
            </div>
            <div class="form-input">
                <label for="prenom">Prenom</label>
                <input type="text" name="prenom" value="" required>
            </div>
            <div class="form-input">
                <label for="email">Email</label>
                <input type="text" name="email" value="" required>
            </div>
            <div class="form-input">
                <label for="password">Password</label>
                <input type="text" name="password" value="" required>
            </div>
            <div class="form-input">
                <label for="matiere">Matiere</label>
                
                <input type="checkbox" name="password" value="mat1" required>
            </div>
            <button type="submit" class="btn">Ajouter Prof</button>
        </form>
    </div>
</div>
</div> -->
