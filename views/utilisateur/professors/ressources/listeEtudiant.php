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
    'January' => 'Janvier',
    'February' => 'Février',
    'March' => 'Mars',
    'April' => 'Avril',
    'May' => 'Mai',
    'June' => 'Juin',
    'July' => 'Juillet',
    'August' => 'Août',
    'September' => 'Septembre',
    'October' => 'Octobre',
    'November' => 'Novembre',
    'December' => 'Décembre'
];

$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');

$idProf = (int) $_SESSION['id_user'];
if ($idProf > 0) {
    $tableFiliere = $professeurTable->getFiliere($idProf);
    $tableClasse = $professeurTable->getClasse($idProf);
    $tableMatiere = $professeurTable->getMatiere($idProf);
}

$studentList = [];
if(!empty($_POST)) {
    $class = isset($_POST['classe']) ? $_POST['classe'] : '';

    if  (!empty($class)) {
        $studentList = $professeurTable->findStudentByClass($class);
    }
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
        <form class="professor-info container" method="post" action="<?= $router->url('professor-listEtudiant') . '?use-link=student-list' ?>">
            <div class="filiere-group">
                <select id="filiere" name="filiere">
                    <option name="filiere" value="filiere">Filière</option>
                    <?php
                        foreach($tableFiliere as $mejor) {
                            $selected = ($filiere === $mejor->getNomFiliere()) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($mejor->getNomFiliere()) . '" ' . $selected . '>' . htmlspecialchars($mejor->getNomFiliere()) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="subject-group">
                <select id="matiere" name="matiere">
                    <option name="matiere"  value="matiere">Matière</option>
                    <?php
                        foreach($tableMatiere as $subject) {
                            $selected = ($matiere === $subject->getNomMatiere()) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($subject->getNomMatiere()) . '" ' . $selected . '>' . htmlspecialchars($subject->getNomMatiere()) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="level-group">
                <select id="classe" name="classe">
                    <option name="classe" value="classe">Niveau</option>
                    <?php
                        foreach($tableClasse as $classe) {
                            $selected = ($class === $classe->getNomClasse()) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($classe->getNomClasse()) . '" ' . $selected . '>' . htmlspecialchars($classe->getNomClasse()) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <input class="submit-btn" type="submit" value="Afficher les Etudiants">
            </div>
        </form>
        <section class="table-container">
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
                <?php foreach($studentList as $student) : ?>
                <tr>
                    <td><?= htmlspecialchars($student->getIdEtudiant()) ?></td>
                    <td><?= htmlspecialchars($student->getCNE()) ?></td>
                    <td><?= htmlspecialchars($student->getNom() . ' ' . $student->getPrenom()) ?></td>
                    <td><input type="checkbox"></td>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </section>
    </div>
    <form method="post" action="<?= $router->url('professor-listEtudiant') . '?use-link=student-list'. '&submit=1' ?>">
        <input class="btn-presence" type="submit" value="Valider et Envoyer">
    </form>
</div>
