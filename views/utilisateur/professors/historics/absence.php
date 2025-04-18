<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Professeur\ProfessorTable;
use App\Connection;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$cinProf = $_SESSION['id_user'];

$tableMatiere = $professeurTable->getMatiere($cinProf);
$tableClasse = $professeurTable->getClasse($cinProf);
$listeEtudiant = $professeurTable->getAbsents($cinProf);

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
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="absence-container">
        <form class="professor-info container" method="post" action="">
            <div class="subject-group">
                <select id="matiere" name="matiere-prof" required>
                    <option value="">Sélectionner une matière</option>
                    <?php
                    foreach ($tableMatiere as $subject) {
                        $selected = ($matiere === $subject->getNomMatiere()) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($subject->getNomMatiere()) . '" ' . $selected . '>' . htmlspecialchars($subject->getNomMatiere()) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="level-group">
                <select id="classe" name="classe-prof" required>
                    <option value="">Sélectionner une classe</option>
                    <?php
                    foreach ($tableClasse as $classe) {
                        $selected = ($class === $classe->getNomClasse()) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($classe->getNomClasse()) . '" ' . $selected . '>' . htmlspecialchars($classe->getNomClasse()) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="submit-group">
                <input class="submit-btn" type="submit" name="submit-first" value="Afficher les Étudiants">
            </div>
        </form>
        <?php if (!empty($listeEtudiant)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Nom et Prénom</th>
                            <th>Nombre d'absences</th>
                            <th>Dates et Créneaux</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero = 1; ?>
                        <?php foreach ($listeEtudiant as $cin => $etudiant): ?>
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($cin) ?></td>
                                <td><?= htmlspecialchars($etudiant->getCne()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNombreAbsences()) ?></td>
                                <td>
                                    <ul class="absences-list">
                                        <?php foreach ($etudiant->getAbsences() as $absence): ?>
                                            <li><?= htmlspecialchars(formatAbsence($absence)) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-data">Aucune absence enregistrée.</p>
        <?php endif; ?>
    </div>
</div>

<?php
// Fonction pour formater les absences
function formatAbsence($absence) {
    // Exemple : "2025-04-12 00:00:00-23:20:00" -> "12/04/2025, 00:00-23:20"
    list($date, $time) = explode(' ', $absence);
    $date = date('d/m/Y', strtotime($date));
    return "$date, $time";
}
?>