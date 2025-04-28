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
use App\Model\Administrateur;
use App\Model\Classe;
use App\Model\Filiere;
use App\Model\Niveau;
use App\Model\Utils\Admin\ClasseFiliere;
use App\Admin\StatisticAdmin;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$table = new StatisticAdmin($pdo);
$tableFiliere = $table->getListeFiliere();
$tableNiveau = $table->getListeNiveau();

$success = null;
$super_admin = null;
$error = null;

$classe = $_GET['id_classe'];
if (isset($classe)) {
    $query = $pdo->prepare('
        SELECT c.*, n.nomNiveau, f.nomFiliere FROM niveau n JOIN classe c 
        ON n.idNiveau = c.idNiveau JOIN filiere f ON f.idFiliere = c.idFiliere WHERE c.idClasse = :idClasse
    ');
    $query->execute(['idClasse' => $classe]);
    $query->setFetchMode(\PDO::FETCH_CLASS, ClasseFiliere::class);
    $result = $query->fetch();

    if (!$result) {
        $success = 0;
        header('location:' . $router->url('gestion-classe'). '?classe=1&p=0&success='.$success);
        exit();
    }

    if (!empty($_POST)) {
        $nomClasse = $_POST['classe'];
        $nomNiveau = $_POST['niveau'];
        $nomFiliere = $_POST['filiere'];

        $query_c = $pdo->prepare('SELECT * FROM filiere WHERE nomFiliere = :nomFiliere');
        $query_c->execute(['nomFiliere' => $nomFiliere]);
        $query_c->setFetchMode(\PDO::FETCH_CLASS, Filiere::class);
        $result_c = $query_c->fetch();

        $query_n = $pdo->prepare('SELECT * FROM niveau WHERE nomNiveau = :nomNiveau');
        $query_n->execute(['nomNiveau' => $nomNiveau]);
        $query_n->setFetchMode(\PDO::FETCH_CLASS, Niveau::class);
        $result_n = $query_c->fetch();

        if($result_c && $result_n) {
            /* Impossible de supprimer le super administrateur, ici, c'est le premier admin */
            $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
            $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
            $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
            $admin_verifie = $query_verifie->fetch();

            if ((string) $admin_verifie->getIDAdmin() === '1') {
                $pdo->prepare('UPDATE classe SET idNiveau = :idNiveau, idFiliere = :idFiliere, nomClasse = :nomClasse WHERE idClasse = :idClasse');
                $pdo->execute([
                    'idNiveau' => $result_n->getIDNiveau(), 
                    'idFiliere' => $result_c->getIDFiliere(), 
                    'nomClasse' => $nomClasse,
                    'idClasse' => $classe
                ]);
                $success = 1;
                header('location:' . $router->url('gestion-classe'). '?classe=1&p=0&success='.$success);
                exit();
            }else {
                $super_admin = 1;
                header('location:' . $router->url('gestion-classe'). '?classe=1&p=0&super_admin='.$super_admin);
                exit();
            }
        } else {
            $error = 1;
        }

        
    }
}else {
    $success = 0;
    header('location:' . $router->url('gestion-classe'). '?classe=1&p=0&success='.$success);
    exit();
}
?>

<div class="prof-list">
    <?php if ($error == 1): ?>
        <div class="alert alert-danger">
            Verifier l'année ou la filiere que vous avez selectionné.
        </div>
    <?php endif ?>
    <div class="intro-prof-matiere">
        <h1> Modifier une classe</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="matiere-modifie container" method="POST">
            <section class="edit-matiere-section">
                <div class="select-classe">
                    <label for="classe">Classe</label>
                    <input type="text" name="classe" id="classe" value="<?= htmlspecialchars($result->getNomClasse()) ?>">
                </div>
                <div>
                    <label for="filiere">Filiere</label>

                    <select name="filiere" class="select-filiere">
                    <?php foreach($tableFiliere as $t): ?>
                        <option value="<?= htmlspecialchars($t->getNomFiliere()) ?>" name="filiere" <?= ($t->getNomFiliere() == $result->getNomFiliere()) ? 'selected' : '' ?>><?= htmlspecialchars($t->getNomFiliere()) ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label for="niveau">Niveau</label>
                    <select name="niveau" class="select-niveau">
                    <?php foreach($tableNiveau as $t): ?>
                        <option value="<?= htmlspecialchars($t->getNomNiveau()) ?>" name="niveau" <?= ($t->getNomNiveau() == $result->getNomNiveau()) ? 'selected' : '' ?>><?= htmlspecialchars($t->getNomNiveau()) ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
            </section>
            <section class="submit-group-matiere">
                <button type="submit" class="submit-btn-matiere">Modifier</button>
                <a class="btn2" href="<?=$router->url('gestion-classe'). '?classe=1&p=0' ?>">Annuler</a>
            </section>

        </form>
    </div>
</div>
