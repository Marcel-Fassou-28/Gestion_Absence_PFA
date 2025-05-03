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
use App\Model\Classe;
use App\Mailer;
use App\Logger;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$mailer = new Mailer();
$success_etudiant = null;
$error = 0;

if (!empty($_POST)) {
    var_dump($_POST);
    $role = 'etudiant';
    $cinEtudiant = $_POST['cin'];
    $nomEtudiant = $_POST['nom'];
    $prenomEtudiant = $_POST['prenom'];
    $emailEtudiant = $_POST['email'];
    $cneEtudiant = $_POST['cne'];

    $classe = $_POST['classe'];
    $query = $pdo->prepare('SELECT * FROM classe WHERE nomClasse = :nomClasse');
    $query->execute(['nomClasse' => $classe]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
    $output = $query->fetch();

    if ($output) {
        $idClasse = $output->getIDClasse();
        $username = $cinEtudiant . '.' . $nomEtudiant;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        if ($result->AddStudentUser($cinEtudiant, $nomEtudiant, $prenomEtudiant,  $emailEtudiant, $username, $password, $cneEtudiant, $idClasse, $role)) {
            $success_etudiant = 1;
            Logger::log("Ajout d'un Etudiant", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
            $mailer->confirmationAccount($nomEtudiant . ' ' . $prenomEtudiant, $emailEtudiant, $username, $_POST['password'], $emailEtudiant);
        } else {
            $success_etudiant = 0;
        }
        header('location: ' . $router->url('liste-etudiants').'?listprof=1&p=0&success_etudiant='. $success_etudiant);
        exit();
    }else {
        $error = 1;
    }

}
?>


<div class="prof-list">
    <?php if ($error === 1): ?>
        <div class="alert alert-danger">
            Entrer un nom de classe valide comme IITE-1, CCN-1, ISIC-1, AP-1, etc...
        </div>
    <?php endif ?>
    <div class="intro-prof-list">
        <h1> Ajouter un etudiant</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div>
                    <label for="cin">CIN</label>
                    <input type="text" name="cin" value="" required>
                </div>
                <div>
                    <label for="cne">CNE</label>
                    <input type="text" name="cne" value="" required>
                </div>
                <div>
                    <label for="classe">Classe</label>
                    <input type="text" name="classe" value="" placeholder="Exemple: IITE-1" required>
                </div>
                <div>
                    <label for="nom">Nom</label>
                <input type="text" name="nom" value="" required>
                </div>
                <div>
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" value="" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" name="email" value="" required>
                </div>
                <div>
                    <label for="password">Password par Defaut</label>
                    <input type="text" name="password" value="" required>
                </div>
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Ajouter</button>
                <button class="btn2" onclick= "window.location.href='<?= $router->url('liste-etudiants').'?listprof=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>

<!--
<div class="contains">
<div class="modif">
    <h2>Entrez les information puis valider</h2>
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
                <label for="cne">CNE</label>
                <input type="text" name="cne" value="" required>
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
                <label for="classe">Idclasse</label>
                <input type="number" name="classe" value="" required>
            </div>
            <button type="submit" class="btn">Ajouter l'etudiant</button>
        </form>
    </div>
</div>
</div> -->
