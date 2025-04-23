<?php

if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\connection;
use App\Model\Classe;
use App\Model\Filiere;
use App\Model\Professeur;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$add = null;
$error = 0;

if (!empty($_POST)) {
    $nomMatiere = $_POST['nomMatiere'];
    $nomClasse = $_POST['nomClasse'];
    $cinProf = $_POST['cinProf'];
    $nomProf = $_POST['nomProf'];
    $prenomProf = $_POST['prenomProf'];

    $query = $pdo->prepare('SELECT * FROM classe WHERE nomClasse = :nomClasse');
    $query->execute(['nomClasse' => $nomClasse]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
    $classe = $query->fetch();

    $query1 = $pdo->prepare('SELECT * FROM professeur WHERE nom = :nomProf AND prenom = :prenomProf AND cinProf = :cinProf');
    $query1->execute(['nomProf' => $nomProf, 'prenomProf' => $prenomProf, 'cinProf' => $cinProf]);
    $query1->setFetchMode(\PDO::FETCH_CLASS, Professeur::class);
    $professeur = $query1->fetch();

    if ($classe && $professeur) {
        $query2 = $pdo->prepare('INSERT INTO matiere(cinProf, nomMatiere, idFiliere , idClasse) VALUES (?, ?, ?, ?)');
        try {
            $query2->execute([$cinProf, $nomMatiere, $classe->getIdFiliere(), $classe->getIDClasse()]);
            $success = 1;
        } catch(PDOException $e) {
            $add = 0;
        }
        header('location:' . $router->url('liste-matiere-admin') .'?matiere=1&p=0&add='.$add);
        exit();

    }else {
        $error = 1;
    }

}
?>

<div class="prof-list">
    <?php if($error == 1): ?>
        <div class="alert alert-danger">
            Veuillez verifier que le CIN fourni et/ou la matière indiquée existent bels et bien
        </div>
    <?php endif ?>
    <div class="intro-prof-matiere">
        <h1> Ajouter un créneau</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="matiere-modifie container" method="POST">
            <section class="edit-matiere-section">
                <div>
                    <label for="nomMatiere">Matiere</label>
                    <input type="text" name="nomMatiere" id="nomMatiere" value="">
                </div>
                <div>
                    <label for="nomClasse">Classe</label>
                    <input type="text" name="nomClasse" id="nomClasse" value="" placeholder="Exemple : IITE-1....">
                </div>
                <div>
                    <label for="cinProf">CIN Prof</label>
                    <input type="text" name="cinProf" id="cinProf" value="">
                </div>
                <div>
                    <label for="nomProf">Nom du Professeur</label>
                    <input type="text" name="nomProf" id="nomProf" value="">
                </div>
                <div>
                    <label for="prenomProf">Prenom du Professeur</label>
                    <input type="text" name="prenomProf" id="prenomProf" value="">
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere">Ajouter</button>
                <button class="btn2" onclick="window.location.href='<?=$router->url('liste-matiere-admin'). '?modifie=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>