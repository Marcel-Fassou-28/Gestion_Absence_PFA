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
use App\Model\Matiere;
use App\Model\Professeur;
use App\Model\Utils\Admin\MatiereProf;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$success = null;
$error = 0;
$matiere = $_GET['id_matiere'];

if (isset($matiere)) {
    $query = $pdo->prepare('
        SELECT m.idMatiere ,m.nomMatiere, p.cinProf, p.nom as nomProf, p.prenom as prenomProf, c.nomClasse FROM professeur p 
        JOIN matiere m ON m.cinProf = p.cinProf JOIN classe c ON m.idClasse = c.idClasse WHERE m.idMatiere = :idMatiere
    ');
    $query->execute(['idMatiere' => $matiere]);
    $query->setFetchMode(\PDO::FETCH_CLASS, MatiereProf::class);
    $result = $query->fetch();

    if (!$result) {
        $success = 0;
        header('location:' . $router->url('liste-matiere-admin') .'?matiere=1&p=0&success='.$success);
        exit();
    }

    if (!empty($_POST)) {
        $nomMatiere = $_POST['nomMatiere'];
        $nomClasse = $_POST['nomClasse'];
        $cinProf = $_POST['cinProf'];
        $nomProf = $_POST['nomProf'];
        $prenomProf = $_POST['prenomProf'];

        $query2 = $pdo->prepare('SELECT * FROM classe WHERE nomClasse = :nomClasse');
        $query2->execute(['nomClasse' => $nomClasse]);
        $query2->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
        $classe = $query2->fetch();

        $query1 = $pdo->prepare('SELECT * FROM professeur WHERE nom = :nomProf AND prenom = :prenomProf AND cinProf = :cin');
        $query1->execute([
            'nomProf' => $nomProf,
            'prenomProf' => $prenomProf,
            'cin' => $cinProf
        ]);
        $query1->setFetchMode(\PDO::FETCH_CLASS, Professeur::class);
        $professeur = $query1->fetch();

        if (($classe && $classe->getNomClasse() === $nomClasse) && ($professeur && $professeur->getNom() === $nomProf && $professeur->getPrenom() == $prenomProf)) {

            $idClasse = $classe->getIDClasse();
            $idFiliere = $classe->getIdFiliere();
            $query1 = $pdo->prepare('UPDATE matiere SET idClasse = :idClasse, idFiliere = :idFiliere, cinProf = :cin WHERE idMatiere = :idMatiere');
            $query1->execute([
                'idClasse' => $idClasse,
                'cin' => $cinProf,
                'idFiliere' => $idFiliere,
                'idMatiere' => $matiere
            ]);
            $success = 1;
            header('location:' . $router->url('liste-matiere-admin') .'?matiere=1&p=0&success='.$success);
            exit();
        }else {
            $error = 1;
        }
    }
}else {
    $success = 0;
    header('location:' . $router->url('liste-matiere-admin') .'?matiere=1&p=0&success='.$success);
    exit();
}
?>

<div class="prof-list">
    <?php if ($error == 1): ?>
        <div class="alert alert-danger">
            Le nom et/ou prenom du professeur fournit n'est pas valide, verifier aussi le CIN.
        </div>
    <?php endif ?>
    <div class="intro-prof-matiere">
        <h1> Modifier une Matière</h1>
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
