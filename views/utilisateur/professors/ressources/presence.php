<?php

use App\Professeur\ProfessorTable;
use App\Professeur\CurrentInfo;
use App\Connection;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);
$currentProfInfo = new CurrentInfo($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$moisEnFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
    'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
    'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');
$dateSql = $date->format('Y-m-d H:i:s');

$cinProf = $_SESSION['id_user'];
$listeEtudiant = $currentProfInfo->getCurrentStudentList($cinProf);

$filiere = $currentProfInfo->getCurrentFiliere($cinProf);
$matiere = $currentProfInfo->getCurrentMatiere($cinProf);
$classe = $currentProfInfo->getCurrentClasse($cinProf)->getNomClasse();
$creneau = $currentProfInfo->getCurrentCreneau($cinProf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $arrayAbsence = $_POST['arrayCheckbox'] ?? [];
    $listeEtudiant = $professeurTable->findStudentByClass($currentProfInfo->getCurrentClasse($cinProf)->getIDClasse());
    $idMatiere = $matiere->getIdMatiere();

    $professeurTable->setAbsence($arrayAbsence, $dateSql, $listeEtudiant, $idMatiere);
}

?>

<div class="presence">
    <div class="intro">
        <h1>Liste des Présences</h1>
        <div class="date-group">
           <span><?= $dateDuJour ?></span>
           <span>Créneau :  <?= $creneau->getHeureDebut() . ' - ' . $creneau->getHeureFin() ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container">
        <section class="professor-info container">
            <div class="filiere-group">
                <span><?= $filiere->getNomFiliere() ?></span>
            </div>
            <div class="subject-group">
                <span><?= $matiere->getNomMatiere() ?></span>
            </div>
            <div class="classe-group">
                <span><?= $classe ?></span>
            </div>
        </section>
        <?php if (!empty($listeEtudiant)): ?>
            <form class="table-container" method="post" action="">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Matricule</th>
                            <th>Nom et Prénom</th>
                            <th>Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero = 1; ?>
                        <?php foreach ($listeEtudiant as $etudiant): ?>
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($etudiant->getCNE()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom()) ?></td>
                                <td>
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="arrayCheckbox[<?= htmlspecialchars($etudiant->getCIN()) ?>]" >
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div>
                    <input class="btn-presence" type="submit" name="submit" value="Valider et Envoyer">
                </div>
            </form>
        </div>
    <?php endif ?>
</div>
