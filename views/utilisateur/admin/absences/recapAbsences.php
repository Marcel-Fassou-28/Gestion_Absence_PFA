<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Admin\adminTable;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeClasse = $list->getAll("classe", "classClasse");
foreach ($listeClasse as $class) {
    $listeMat = $list->getAllMatiereByclass($class->getIdClasse());
    if (!empty($listeMat)) {
        $listeEtudiant = $list->getStudentByClass($class->getNomClasse());


        ?>

        <div class="global">
            <div id="liste">
                <h1 id="titre">Recapitulatif des abscences des etudiants en heures</h1>
                <br>
                <h3>classe : <?= $class->getNomClasse(); ?></h3>
                <hr>

                <div class="list-tri justificatifs table-responsive">
                    <table class="prof-table">
                        <thead class="heads">
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Cin</th>
                            <?php

                            foreach ($listeMat as $clas) { ?>
                                <th><?= $clas->getNomMatiere(); ?></th>
                            <?php } ?>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($listeEtudiant as $etudiant) { ?>
                                <tr>
                                    <td><?= $etudiant->getNom(); ?></td>
                                    <td><?= $etudiant->getPrenom(); ?></td>
                                    <td><?= $etudiant->getCin(); ?></td>
                                    <?php
                                    $cin = $etudiant->getCin();
                                    foreach ($listeMat as $clas) { 
                                        
                                        $idmatiere = $clas->getIdMatiere();
                                       
                                        $result = $list->getAbsenceStudentByMatiere($cin,$idmatiere); ?>
                                        <td><?= $result; ?></td>
                                    <?php }
                            } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php } 
} 
?>