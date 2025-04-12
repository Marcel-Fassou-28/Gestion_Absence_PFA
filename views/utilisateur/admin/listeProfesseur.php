<?php

use App\adminTable;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeDepart = $list->getAll("departement", "classDepartement");
$listeFiliere = $list->getAll("filiere", "classFiliere");
$listeClasse = $list->getAll("classe", "classClasse");
$listeProf = $list->getAll("professeur", "classProf");


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
    <h1> Liste Des Professeurs</h1>
    <hr>
    <div class="form-tri">
        <form action="" class="tri-list" method="POST">
            <select name="departement" id="tri" onchange="this.form.submit()">
                <option value="defaut">tous les departement</option>
                <?php
                foreach ($listeDepart as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomDepartement()); ?>" <?= (isset($_POST['departement']) && $_POST['departement'] === $row->getNomDepartement() ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomDepartement()); ?>
                    </option><?php
                }
                ?>
            </select>
            <select name="filiere" id="tri" onchange="this.form.submit()">
                <option value="defaut">toutes les filieres</option>
                <?php
                foreach ($listeFiliere as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomFiliere()); ?>" <?= (isset($_POST['filiere']) && $_POST['filiere'] === $row->getNomFiliere() ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomFiliere()); ?>
                    </option><?php
                }
                ?>
            </select>
            <select name="classe" id="tri">
                <option value="defaut">toutes les classe</option>
                <?php
                foreach ($listeClasse as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomClasse()); ?>" <?= (isset($_POST['classe']) && $_POST['classe'] === $row->getNomClasse() ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomClasse()); ?>
                    </option><?php
                }
                ?>
            </select>
            <input type="submit" value="Trier" name="submit">
        </form>
    </div>

    <div class="list-tri">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <?php if ($_SESSION['role'] === "professeur")
                        echo "<th>CIN</th>"; ?>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($listeProf as $row) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row->getNom()); ?></td>
                        <td><?= htmlspecialchars($row->getPrenom()); ?></td>
                        <?php if ($_SESSION['role'] === "professeur")
                            echo "<td>" . htmlspecialchars($row->getCIN()) . "</td>"; ?>
                        <td><?= htmlspecialchars($row->getEmail()); ?></td>
                    </tr><?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>