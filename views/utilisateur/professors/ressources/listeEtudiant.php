<?php

use App\Connection;
use App\Professeur\ProfessorTable;

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
$listeEtudiant = $professeurTable->findStudent($cinProf);
$listDesAbsents = $professeurTable->getAllStudentAbsenceState($cinProf);
$listeComplete = $professeurTable->getAllStudentList($listeEtudiant, $listDesAbsents);

$filiere = ''; 
$matiere = '';
$class = '';

$tableFiliere = $professeurTable->getFiliere($cinProf);
$tableClasse = $professeurTable->getClasse($cinProf);
$tableMatiere = $professeurTable->getMatiere($cinProf);
$listeEtudiant = $professeurTable->findStudent($cinProf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $filiere = $_POST['filiere-prof'] ?? '';
    $matiere = $_POST['matiere-prof'] ?? '';
    $class = $_POST['classe-prof'] ?? '';

    $idMatiere = (int) array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere)[array_key_first(array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere))]->getIdMatiere();
    $idClasse = (int) array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class)[array_key_first(array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class))]->getIDClasse();

    if (!empty($class) && !empty($matiere)) {

        $listeEtudiants = $professeurTable->findStudentByClass($idClasse);
        $listAbsents = $professeurTable->getNbrAbsence($cinProf, $idClasse, $idMatiere);
        $listeComplete = $professeurTable->getAllStudentList($listeEtudiants, $listAbsents);
    }
}
?>

<div class="presence">
    <div class="intro">
        <h1>Liste des Etudiants</h1>
        <div class="date-group-etudiant">
            <input type="text" value="<?= $dateDuJour ?>" readonly>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container">
        <form class="professor-info container" method="post" action="">
            <div class="filiere-group">
                <select id="filiere" name="filiere-prof" required>
                    <option value="">Filière</option>
                    <?php foreach($tableFiliere as $mejor): ?>
                        <option value="<?= htmlspecialchars($mejor->getNomFiliere()) ?>" <?= $filiere === $mejor->getNomFiliere() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mejor->getNomFiliere()) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="subject-group">
                <select id="matiere" name="matiere-prof" required>
                    <option name="matiere-prof"  value="">Matière</option>
                    <?php foreach($tableMatiere as $subject): ?>
                        <option value="<?= htmlspecialchars($subject->getNomMatiere()) ?>" <?= $matiere === $subject->getNomMatiere() ? 'selected' : ''?>>
                            <?= htmlspecialchars($subject->getNomMatiere()) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="level-group">
                <select id="classe" name="classe-prof" required>
                    <option name="classe-prof" value="" >Niveau</option>
                    <?php foreach($tableClasse as $classe): ?>
                        <option value="<?= htmlspecialchars($classe->getNomClasse()) ?>" <?= $class === $classe->getNomClasse() ? 'selected' : ''?>>
                            <?= htmlspecialchars($classe->getNomClasse()) ?>
                        </option> 
                    <?php endforeach ?>
                </select>
            </div>
            <div>
                <input class="submit-btn" type="submit" name="submit" value="Afficher les Etudiants">
            </div>
        </form>
        <?php if (!empty($listeEtudiant)): ?>
            <section class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Nom et Prénom</th>
                            <th>Nombre d'Absence</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero = 1; ?>
                        <?php foreach ($listeComplete as $etat): ?>
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($etat->getCINEtudiant()) ?></td>
                                <td><?= htmlspecialchars($etat->getCNE()) ?></td>
                                <td><?= htmlspecialchars($etat->getNom() . ' ' . $etat->getPrenom()) ?></td>
                                <td><?= htmlspecialchars($etat->getNbrAbsence()) ?></td>
                                <td><button class="show-state" data-modal-id="modal-<?= $numero ?>">Voir plus</button></td>
                            </tr>
                            <div class="modal" id="modal-<?= $numero ?>">
                                <div class="modal-overlay"></div>
                                <div class="modal-content">
                                    <div>
                                        <p>CIN: </p>
                                        <p><?= htmlspecialchars($etat->getCINEtudiant()) ?></p>
                                    </div>
                                    <div>
                                        <p>CNE: </p>
                                        <p><?= htmlspecialchars($etat->getCNE()) ?></p>
                                    </div>
                                    <div>
                                        <p>Nom: </p>
                                        <p><?= htmlspecialchars($etat->getNom()) ?></p>
                                    </div>
                                    <div>
                                        <p>Prénom: </p>
                                        <p><?= htmlspecialchars($etat->getPrenom()) ?></p>
                                    </div>
                                    <div>
                                        <p>Nombre d'Absence: </p>
                                        <p><?= htmlspecialchars($etat->getNbrAbsence()) ?></p>
                                    </div>
                                    <div class="modal-buttons">
                                        <button class="btn-modal close-modal">Fermer</button>
                                        <button class="btn-modal">
                                            <a href="mailto:<?= $etat->getEmail() ?>">Contactez</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    <?php endif ?>
</div>
