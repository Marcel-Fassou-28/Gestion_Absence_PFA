<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\ProfessorTable;
use App\Connection;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);

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
$listeEtudiant = [];
$filiere = ''; 
$matiere = '';
$class = '';
$submittedFirst = false;
$submittedSecond = false;

$tableFiliere = $professeurTable->getFiliere($cinProf);
$tableClasse = $professeurTable->getClasse($cinProf);
$tableMatiere = $professeurTable->getMatiere($cinProf);
$listeEtudiant = $professeurTable->findStudent($cinProf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-first'])) {
    $filiere = $_POST['filiere-prof'] ?? '';
    $matiere = $_POST['matiere-prof'] ?? '';
    $class = $_POST['classe-prof'] ?? '';
    $submittedFirst = true;
    if (!empty($class)) {
        $listeEtudiant = $professeurTable->findStudentByClass($class);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-second'])) {
    $matiere = $_POST['matiere'] ?? '';
    $arrayAbsence = $_POST['arrayCheckbox'] ?? [];
    
    $listeEtudiant = $professeurTable->findStudentByClass($_POST['classe-prof']);
    $idMatiere = array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere)[array_key_first(array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere))]->getIdMatiere();

    $professeurTable->setAbsence($arrayAbsence, $dateSql, $listeEtudiant, $idMatiere);
    $submittedSecond = true;
}

?>

<div class="presence">
    <div class="intro">
        <h1>Liste des Présences</h1>
        <div class="date-group">
            <input type="text" value="<?= $dateDuJour ?>" readonly>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container">
        <form class="professor-info container" method="post" action="">
            <div class="filiere-group">
                <select id="filiere" name="filiere-prof">
                    <option name="filiere-prof" value="filiere">Filière</option>
                    <?php
                        foreach($tableFiliere as $mejor) {
                            $selected = ($filiere === $mejor->getNomFiliere()) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($mejor->getNomFiliere()) . '" ' . $selected . '>' . htmlspecialchars($mejor->getNomFiliere()) . '</option>';
                        }
                    ?>
                </select>
            </div>
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
                <select id="classe" name="classe-prof">
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
        <?php if (!empty($listeEtudiant)): ?>
            <form class="table-container" method="post" action="">
                <input type="hidden" name="matiere" value="<?= htmlspecialchars($matiere) ?>">
                <input type="hidden" name="classe-prof" value="<?= htmlspecialchars($class) ?>">
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
                                        <input type="checkbox" name="arrayCheckbox[<?= htmlspecialchars($etudiant->getCIN()) ?>]">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div>
                    <input class="btn-presence" type="submit" name="submit-second" value="Valider et Envoyer">
                </div>
            </form>
        </div>
    <?php endif ?>
</div>
