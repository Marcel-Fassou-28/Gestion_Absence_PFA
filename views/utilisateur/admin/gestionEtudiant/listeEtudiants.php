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

/*<a href="<?= $urlUser['modification']; ?>">modifier</a>*/
use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeDepart = $list->getAll("departement", "classDepartement");
$listeFiliere = $list->getAll("filiere", "classFiliere");
$listeClasse = $list->getAll("classe", "classClasse");
$listeEtudiant = $list->getAll("etudiant", "classEtudiant");
$nbreEtudiants = 0;

/*if (isset($_POST['departement']) && $_POST['departement'] !== 'defaut') {
    $departement = $_POST['departement'];
    $listeFiliere = $list->fieldsByDepartement($departement);
    $listeProf = $list->getprofByDepartement($departement);
}*/

if (isset($_POST['filiere']) && $_POST['filiere'] !== 'defaut') {
    $filiere = $_POST['filiere'];
    $listeClasse = $list->classByFields($filiere);
    $listeEtudiant = $list->getStudentByFiliere($filiere);
}

if (isset($_POST['classe']) && $_POST['classe'] !== 'defaut') {
    $classe = $_POST['classe'];
    $listeEtudiant = $list->getStudentByClass($classe);
}
?>
<div class="prof-list">
    <h1> Liste Des Etudiants</h1>
    <hr>
    <div class="form-tri">
        <form action="" class="tri-list" method="POST">
            
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

    <div class="list-tri table-responsive">
        <table class="prof-table">
            <thead class="heads">
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>CIN</th>
                    <th>CNE</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <?php
            foreach ($listeEtudiant as $row) { ?>
                <tr>
                    <td><?= ++$nbreEtudiants; ?></td>
                    <td><?= htmlspecialchars($row->getNom()); ?></td>
                    <td><?= htmlspecialchars($row->getPrenom()); ?></td>
                    <td> <?= htmlspecialchars($row->getCIN());?></td>
                    <td> <?= htmlspecialchars($row->getCNE());?></td>
                    <td><?= htmlspecialchars($row->getEmail()); ?></td>
                    <td class="btns">
                        
                        <form method="GET" action="modifier-student">
                            <input type="hidden" name="cin" value="<?= htmlspecialchars($row->getCIN()); ?>">
                            <input type="hidden" name="modif" value="<?= 1; ?>">
                            <button class="btn1  " type="submit">Modifier</button>
                        </form>
                        <form method="POST" action="modifier-student">
                            <input type="hidden" name="cin" value="<?= htmlspecialchars($row->getCIN()); ?>">
                            <button class="btn2  " type="submit">supprimer </button>
                        </form>

                    </td>

                </tr><?php
            }
            ?>
        </table>
        
        
    </div>
    <form method="POST" action="ajouter-Etudiant?modif=1">
            <button class="btn1 btn-ajout" type="submit">Ajouter un Etudiant</button>
        </form>
</div>