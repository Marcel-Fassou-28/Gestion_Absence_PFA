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
        <form action="" class="matiere-modifie container" method="POST">
            <section class="edit-matiere-section">
                <div>
                    <label for="nomMatiere">Matiere</label>
                    <input type="text" name="nomMatiere" id="nomMatiere" value="<?= htmlspecialchars($result->getNomMatiere()) ?>">
                </div>
                <div>
                    <label for="nomClasse">Classe</label>
                    <input type="text" name="nomClasse" id="nomClasse" value="<?= htmlspecialchars($result->getNomClasse()) ?>" placeholder="Exemple : IITE-1....">
                </div>
                <div>
                    <label for="cinProf">CIN Prof</label>
                    <input type="text" name="cinProf" id="cinProf" value="<?= htmlspecialchars($result->getCinProf()) ?>">
                </div>
                <div>
                    <label for="nomProf">Nom du Professeur</label>
                    <input type="text" name="nomProf" id="nomProf" value="<?= htmlspecialchars($result->getNomProf()) ?>">
                </div>
                <div>
                    <label for="prenomProf">Prenom du Professeur</label>
                    <input type="text" name="prenomProf" id="prenomProf" value="<?= htmlspecialchars($result->getPrenomProf()) ?>">
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere">Modifier</button>
                <button class="btn2" onclick="window.location.href='<?=$router->url('liste-matiere-admin'). '?modifie=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>