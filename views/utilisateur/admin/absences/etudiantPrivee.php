<?php
namespace APP;
use App\Connection;
use App\Admin\adminTable;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeClasse = $list->getAll("classe", "classClasse");

?>
<div class="all global">


    <?php foreach ($listeClasse as $class) {
        $allStudent = $list->getStudentByClass($class->getNomClasse());
        if (!empty($allStudent)) {
            ?>
            <div class="classe">

                <?php
                $listeMat = $list->getAllMatiereByclass($class->getIdClasse());
                if (!empty($listeMat)) {
                    foreach ($listeMat as $mat) {
                        $listeEtudiant = $list->getPrivateStudentToPastExamByMatiere($mat->getIdMatiere());
                        if (!empty($listeEtudiant)) {
                            ?>

                            


                            <div class="global">
                                <div id="liste">
                                    <h1 id="titre">liste des etudiants</h1>

                                    <h3>classe : <?= $class->getNomClasse(); ?></h3>
                                    <h3> <?= 'matiere: ' . $mat->getNomMatiere(); ?></h3>
                                    <div class="list-tri justificatifs table-responsive">
                                        <table class="prof-table">
                                            <thead class="heads">
                                                <th>Nom</th>
                                                <th>Prenom</th>
                                                <th>Cin</th>

                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($listeEtudiant as $etudiant) {



                                                    ?>

                                                    <tr>
                                                        <td><?= $etudiant->getNom(); ?></td>
                                                        <td><?= $etudiant->getPrenom(); ?></td>
                                                        <td><?= $etudiant->getCIN(); ?></td>

                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    }
                }
        }
    }
    ?>