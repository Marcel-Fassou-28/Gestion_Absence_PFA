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
use App\Model\Creneaux;
use App\Model\Matiere;
use App\Model\Professeur;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$success = null;
$error = 0;
$creneau = $_GET['id_creneau'];

if (isset($creneau)) {
    $query = $pdo->prepare('
            SELECT C.jourSemaine, C.cinProf, C.heureDebut, C.heureFin, C.id, P.nom as nomProf, P.prenom as prenomProf , Cl.nomClasse, M.nomMatiere
            FROM Creneaux C JOIN Professeur P ON C.cinProf = P.cinProf JOIN Matiere M ON C.idMatiere = M.idMatiere
            JOIN Classe Cl ON M.idClasse = Cl.idClasse WHERE C.id = :id_creneau');
    $query->execute(['id_creneau' => $creneau]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Creneaux::class);
    $result = $query->fetch();

    if (!$result) {
        $success = 0;
        header('location:' . $router->url('gestion-creneau') .'?listprof=1&p=0&success='.$success);
        exit();
    }

    if (!empty($_POST)) {
        $heureDebut = $_POST['heuredebut'];
        $heureFin = $_POST['heurefin'];
        $jourSemaine = $_POST['joursemaine'];
        $nomClasse = $_POST['classe'];
        $nomMatiere = $_POST['matiere'];
        $nomProf = $_POST['nomprof'];
        $prenomProf = $_POST['prenomprof'];

        $query2 = $pdo->prepare('SELECT * FROM matiere WHERE nomMatiere = :nomMatiere');
        $query2->execute(['nomMatiere' => $nomMatiere]);
        $query2->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
        $matiere = $query2->fetch();

        $query1 = $pdo->prepare('SELECT * FROM professeur WHERE nom = :nomProf AND prenom = :prenomProf AND cinProf = :cin');
        $query1->execute([
            'nomProf' => $nomProf,
            'prenomProf' => $prenomProf,
            'cin' => $result->getCinProf()
        ]);
        $query1->setFetchMode(\PDO::FETCH_CLASS, Professeur::class);
        $professeur = $query1->fetch();

        if (($matiere && $matiere->getNomMatiere() === $nomMatiere) && ($professeur && $professeur->getNom() === $nomProf && $professeur->getPrenom() == $prenomProf)) {

            $idMatiere = $matiere->getIdMatiere();
            $query1 = $pdo->prepare('UPDATE creneaux SET heureDebut = :hd, heureFin = :hf, jourSemaine = :js, idMatiere = :idMatiere, cinProf = :cin WHERE id = :id_creneau');
            $query1->execute([
                'hd' => $heureDebut, 
                'hf' => $heureFin, 
                'js' => $jourSemaine, 
                'idMatiere' => $idMatiere,
                'cin' => $professeur->getCIN(),
                'id_creneau' => $creneau
            ]);
            $success = 1;
            header('location:' . $router->url('gestion-creneau') .'?listprof=1&p=0&success='.$success);
            exit();
        }else {
            $error = 1;
        }
    }
}else {
    $success = 0;
    header('location:' . $router->url('gestion-creneau') .'?listprof=1&p=0&success='.$success);
    exit();
}
?>

<div class="prof-list">
    <?php if ($error == 1): ?>
        <div class="alert alert-danger">
            Le nom et/ou prenom du professeur ou le nom de la matiere fournie n'est pas valide.
        </div>
    <?php endif ?>
    <div class="intro-prof-list">
        <h1> Modifier un Créneaux</h1>
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
                    <input type="time" name="heuredebut" id="heuredebut" value="<?= htmlspecialchars($result->getHeureDebut()) ?>">
                </div>
                <div class="creneau-fin">
                    <label for="">Heure de Fin</label>
                    <input type="time" name="heurefin" id="heurefin" value="<?= htmlspecialchars($result->getHeureFin()) ?>">
                </div>
                <div class="joursemaine">
                    <label for="">Jour de la Semaine</label>
                    <input type="text" name="joursemaine" id="joursemaine" value="<?= htmlspecialchars($result->getJourSemaine()) ?>">
                </div>
                <div class="classe">
                    <label for="">Classe</label>
                    <input type="text" name="classe" id="classe" value="<?= htmlspecialchars($result->getNomClasse()) ?>">
                </div>
                <div class="matiere">
                    <label for="">Matiere</label>
                    <input type="text" name="matiere" id="matiere" value="<?= htmlspecialchars($result->getNomMatiere()) ?>">
                </div>
                <div class="nomprof">
                    <label for="">Nom du Professeur</label>
                    <input type="text" name="nomprof" id="nomprof" value="<?= htmlspecialchars($result->getNomProf()) ?>">
                </div>
                <div class="prenomprof">
                    <label for="">Prenom du Professeur</label>
                    <input type="text" name="prenomprof" id="prenomprof" value="<?= htmlspecialchars($result->getPrenomProf()) ?>">
                </div>
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Modifier</button>
                <button class="btn2" onclick="window.location.href='<?=$router->url('gestion-creneau'). '?listprof=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>
