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

$listeId = $list->getAbsenceMatiere();

foreach ($listeId as $id):
    $listeMatiere = $list->getMatiereById($id['idMatiere']);

    $listeClasse = $list->getClassById($listeMatiere[0]->getIdClasse());

    $listeEtudiant = $list->getAbsenceAllStudentByMatiere($id['idMatiere'], $id['date']);
    ?>
    <div class="global">
        <div class="absentet">
            <h1> Liste Des Abscences</h1>
            <div class="infoA">
            <pre><p>Classe:<?= $listeClasse[0]->getNomClasse(); ?>
            </p></pre>
            <pre><p>Matiere:<?= $listeMatiere[0]->getNomMatiere(); ?>
            </p></pre>
            </div>
            <hr>
            <div class="list-tri">
                <table class="prof-table">
                    <thead class="heads">
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                        </tr>
                    </thead>
                    <?php
                    $nbreProf = 0;
                    foreach ($listeEtudiant as $row) { ?>
                        <tr>
                            <td><?= ++$nbreProf; ?></td>
                            <td><?= htmlspecialchars($row->getNom()); ?></td>
                            <td><?= htmlspecialchars($row->getPrenom()); ?></td>
                        </tr><?php
                    }
                    ?>
                </table>
            </div>

        </div>
    </div>
<?php endforeach; ?>