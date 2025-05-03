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
use App\Model\Matiere;
use App\Model\Professeur;
use App\Logger;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$add = null;
$error = 0;

if (!empty($_POST)) {
    $heureDebut = $_POST['heuredebut'];
    $heureFin = $_POST['heurefin'];
    $jourSemaine = $_POST['joursemaine'];
    $nomClasse = $_POST['classe'];
    $nomMatiere = $_POST['matiere'];
    $nomProf = $_POST['nomprof'];
    $prenomProf = $_POST['prenomprof'];

    $query = $pdo->prepare('SELECT * FROM matiere WHERE nomMatiere = :nomMatiere');
    $query->execute(['nomMatiere' => $nomMatiere]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
    $matiere = $query->fetch();

    $query1 = $pdo->prepare('SELECT * FROM professeur WHERE nom = :nomProf AND prenom = :prenomProf');
    $query1->execute(['nomProf' => $nomProf, 'prenomProf' => $prenomProf]);
    $query1->setFetchMode(\PDO::FETCH_CLASS, Professeur::class);
    $professeur = $query1->fetch();

    if (($matiere && $matiere->getNomMatiere() === $nomMatiere) && ($professeur && $professeur->getNom() == $nomProf && $professeur->getPrenom() == $prenomProf)) {
        $query2 = $pdo->prepare('INSERT INTO creneaux(jourSemaine, heureDebut, heureFin, cinProf, idMatiere) VALUES (?, ?, ?, ?, ?)');
        try {
            $query2->execute([$jourSemaine, $heureDebut, $heureFin, $professeur->getCIN(), $matiere->getIdMatiere()]);
            $success = 1;
            Logger::log("Ajout d'un Créneau", 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
        } catch(PDOException $e) {
            $add = 0;
        }
        header('location:' . $router->url('gestion-creneau') .'?listprof=1&p=0&add='.$add);
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
    <div class="intro-prof-list">
        <h1> Ajouter un créneau</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div class="creneau-debut">
                    <label for="">Heure de debut</label>
                    <input type="time" name="heuredebut" id="heuredebut" value="">
                </div>
                <div class="creneau-fin">
                    <label for="">Heure de Fin</label>
                    <input type="time" name="heurefin" id="heurefin" value="">
                </div>
                <div class="joursemaine">
                    <label for="">Jour de la Semaine</label>
                    <input type="text" name="joursemaine" id="joursemaine" value="">
                </div>
                <div class="matiere">
                    <label for="">Matiere</label>
                    <input type="text" name="matiere" id="matiere" value="">
                </div>
                <div class="nomprof">
                    <label for="">Nom du Professeur</label>
                    <input type="text" name="nomprof" id="nomprof" value="">
                </div>
                <div class="prenomprof">
                    <label for="">Prenom du Professeur </label>
                    <input type="text" name="prenomprof" id="prenomprof" value="">
                </div>
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Ajouter</button>
                <button class="btn2" onclick="window.location.href='<?=$router->url('gestion-creneau'). '?listprof=1&p=0' ?>'">Annuler</button>/button>
            </section>

        </form>
    </div>
</div>