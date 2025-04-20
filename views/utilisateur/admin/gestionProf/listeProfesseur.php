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
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*<a href="<?= $urlUser['modification']; ?>">modifier</a>*/
use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$listeDepart = $list->getAll("departement", "classDepartement");
$listeFiliere = $list->getAll("filiere", "classFiliere");
$listeClasse = $list->getAll("classe", "classClasse");
$listeProf = $list->getAll("professeur", "classProf");
$nbreProf = 0;

if (isset($_POST['departement']) && $_POST['departement'] !== 'defaut') {
    $departement = $_POST['departement'];
    $listeFiliere = $list->fieldsByDepartement($departement);
    $listeProf = $list->getprofByDepartement($departement);
}

if (isset($_POST['filiere']) && $_POST['filiere'] !== 'defaut') {
    $filiere = $_POST['filiere'];
    $listeClasse = $list->classByFields($filiere);
    $listeProf = $list->getProfByFiliere($filiere);
}

if (isset($_POST['classe']) && $_POST['classe'] !== 'defaut') {
    $classe = $_POST['classe'];
    $listeProf = $list->getProfByClass($classe);
} 
?>
<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Liste Des Professeurs</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <!-- Privilègier les liens et non les formulaires -- -->
            <!--<form  method="POST" action="ajouter-prof?modif=1">
                <button class="btn-ajout" type="submit">Ajouter un Professeur</button>
            </form> -->
            <a href="" class="btn-ajout">Ajouter un Professeur</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-departement">
            <select name="departement" id="tri" onchange="this.form.submit()">
                <option value="defaut">Département</option>
                <?php
                foreach ($listeDepart as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomDepartement()); ?>" <?= (isset($_POST['departement']) && $_POST['departement'] === $row->getNomDepartement() ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomDepartement()); ?>
                    </option><?php
                }
                ?>
            </select>
            </div>
            <div class="list-filiere">
            <select name="filiere" id="tri" onchange="this.form.submit()">
                <option value="defaut">Filières</option>
                <?php
                foreach ($listeFiliere as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomFiliere()); ?>" <?= (isset($_POST['filiere']) && $_POST['filiere'] === $row->getNomFiliere() ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomFiliere()); ?>
                    </option><?php
                }
                ?>
            </select>
            </div>
            <div class="list-classe">
            <select name="classe" id="tri">
                <option value="defaut">Classe</option>
                <?php
                foreach ($listeClasse as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomClasse()); ?>" <?= (isset($_POST['classe']) && $_POST['classe'] === $row->getNomClasse() ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomClasse()); ?>
                    </option><?php
                }
                ?>
            </select>
            </div>
            <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>

        </form>
    </div>
    <div class="list-tri-table">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>CIN</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($listeProf as $row) { ?>
                <tr>
                    <td><?= ++$nbreProf; ?></td>
                    <td><?= htmlspecialchars($row->getNom()) ?></td>
                    <td><?= htmlspecialchars($row->getPrenom()) ?></td>
                    <td><?= htmlspecialchars($row->getCIN()) ?></td>
                    <td><?= htmlspecialchars($row->getEmail()) ?></td>
                    <td class="btns">
                        <a href="" class="btn1">Modifier</a>
                        <a href="" class="btn2">Supprimer</a>
                        <!--<form method="GET" action="modifier-prof">
                            <input type="hidden" name="cin" value="/ htmlspecialchars($row->getCIN()); ?>">
                            <input type="hidden" name="modif" value="<= 1; ?>">
                            <button class="btn1" type="submit">Modifier</button>
                        </form>
                        <form method="POST" action="modifier-prof">
                            <input type="hidden" name="cin" value="<= htmlspecialchars($row->getCIN()); ?>">
                            <button class="btn2" type="submit">supprimer </button>
                        </form>-->
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>