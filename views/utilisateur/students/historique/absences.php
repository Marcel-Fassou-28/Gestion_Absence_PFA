<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'etudiant') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

$title = "Etudiant";

use App\Connection;
use App\Logger;

$pdo = Connection::getPDO();
$cinEtudiant = $_SESSION['id_user'];
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$filtreDate = date('Y-m-d');
if (!empty($_POST)) {
    $filtreDate = $_POST['filtre_date'] != '' ? $_POST['filtre_date'] : date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT a.idAbsence, a.date, m.nomMatiere, j.statut, j.message, j.nomFichierJustificatif
        FROM Absence a
        JOIN Matiere m ON a.idMatiere = m.idMatiere
        LEFT JOIN Justificatif j ON a.idAbsence = j.idAbsence
        WHERE a.cinEtudiant = :cin
        AND DATE(a.date) <= :filtreDate
        ORDER BY a.date DESC
    ");
    $stmt->execute([
        'cin' => $cinEtudiant,
        'filtreDate' => $filtreDate
    ]);
    $absences = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare("
        SELECT a.idAbsence, a.date, m.nomMatiere, j.statut, j.message, j.nomFichierJustificatif
        FROM Absence a
        JOIN Matiere m ON a.idMatiere = m.idMatiere
        LEFT JOIN Justificatif j ON a.idAbsence = j.idAbsence
        WHERE a.cinEtudiant = :cin
        AND DATE(a.date) <= :filtreDate
        ORDER BY a.date DESC
    ");
    $stmt->execute([
        'cin' => $cinEtudiant,
        'filtreDate' => $filtreDate
    ]);
    $absences = $stmt->fetchAll(PDO::FETCH_ASSOC);  
}

?>

<div class="prof-list">
    <div class="intro-prof-list">
        <h1>Historique des absences</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form class="tri-list container" method="post" action="">
            <input type="date" name="filtre_date" class="date-picker" value="<?= date('Y-m-d', strtotime($filtreDate)) ?>" required>
            <div class="submit-group">            
            <input class="submit-btn" type="submit" name="submit-first" value="Filtrer">
            </div>
        </form>
        <a class="submit-btn" href="<?=$router->url('etudiant-justificatifs').'?messagerie=1&listprof=1'?>">Soumettre un justificatif</a>
    </div>
    <div class="list-tri-table">
        <table>
            <thead>
            <tr>
                <th>N°</th>
                <th>Date</th>
                <th>Module</th>
                <th>Statut</th>
            </tr>
            </thead>
            <tbody>
                <?php $numero = 1; ?>
                <?php if (count($absences) !== 0): ?>
                <?php foreach ($absences as $absence): ?>
                <tr>
                    <td><?= $numero++ ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($absence['date']))) ?></td>
                    <td><?= htmlspecialchars($absence['nomMatiere']) ?></td>
                    <td><?= $absence['statut'] ?? 'Non justifiée' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                    <tr>
                        <td colspan="5"><p class="no-data">Aucune absence trouvée.</p></td>
                    </tr>
            <?php endif ?>

            
        </table>
    </div>
</div>
