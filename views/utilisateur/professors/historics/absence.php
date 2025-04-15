<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Professeur\ProfessorTable;
use App\Connection;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i:s');
$cinProf = $_SESSION['id_user'];

$listeEtudiant = $professeurTable->getAbsents($cinProf);
$tableMatiere = $professeurTable->getMatiere($cinProf);
$tableClasse = $professeurTable->getClasse($cinProf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-first'])) {
    $class = $_POST['classe-prof'] ?? '';
    $matiere = $_POST['matiere-prof'] ?? '';

    $idMatiere = array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere)[array_key_first(array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere))]->getIdMatiere();
    $idClasse = array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class)[array_key_first(array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class))]->getIDClasse();
    $listeEtudiant = $professeurTable->getNbrAbsence($cinProf, $idClasse , $idMatiere);
}
?>

<div class="absence">
    <div class="intro">
        <h1>Historique des absences</h1>
    </div>
    <div class="hr"></div>
    <div class="absence-container">
        <form class="professor-info container" method="post" action="">
            <div class="subject-group">
                <select id="matiere" name="matiere-prof">
                    <option name="matiere-prof"  value="matiere">Matière</option>
                    <?php
                        foreach($tableMatiere as $subject) {
                            $selected = ($matiere === $subject->getNomMatiere()) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($subject->getNomMatiere()) . '" ' . $selected . '>' . htmlspecialchars($subject->getNomMatiere()) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="level-group">
                <select id="classe" name="classe-prof" required>
                    <option name="classe-prof" value="classe">Niveau</option>
                    <?php
                        foreach($tableClasse as $classe) {
                            $selected = ($class === $classe->getNomClasse()) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($classe->getNomClasse()) . '" ' . $selected . '>' . htmlspecialchars($classe->getNomClasse()) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <input class="submit-btn" type="submit" name="submit-first" value="Afficher les Etudiants">
            </div>
        </form>
        <?php if ($listeEtudiant[0]->getCNE() != null): ?>
        <table class="table-container">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Matricule</th>
                    <th>Nom et Prénom</th>
                    <th>Nombre d'absence</th>
                </tr>
            </thead>
            <tbody>
            <?php $numero = 1; ?>
                        <?php foreach ($listeEtudiant as $etudiant): ?>
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($etudiant->getCNE())?></td>
                                <td><?= htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNbrAbsence())?></td>
                            </tr>
                        <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert-absence">Il n'y a pas de personne ayant aumoins une absence</div>
        <?php endif ?>
</div>