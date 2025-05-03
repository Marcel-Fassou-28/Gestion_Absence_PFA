<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'etudiant') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

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
    </div>
    <div class="list-tri-table">
        <table>
            <thead>
            <tr>
                <th>N°</th>
                <th>Date</th>
                <th>Module</th>
                <th>Statut</th>
                <th>Action</th>
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
                    <td>
                        <?php if ($absence['statut'] === null): ?>
                            <form method="post" enctype="multipart/form-data" style="display:inline;">
                                <input type="hidden" name="idAbsence" value="<?= $absence['idAbsence'] ?>">
                                <input type="file" name="justificatif" accept=".jpg,.png,.pdf,.jpeg" required>
                                <input type="text" name="message" placeholder="Message" required style="color:black">
                                <button type="submit" name="soumettre_justificatif" class="btn-soumettre">Soumettre</button>
                            </form>
                        <?php else: ?>
                            <a href="<?= $router->url('etudiant-messagerie').'?messagerie=1&listprof=1' ?>">
                            <button type="button" class="btn-soumettre">Voir détails</button></a>
                        <?php endif; ?>
                    </td>
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

<?php
// Traitement de la soumission d’un justificatif
if (isset($_POST['soumettre_justificatif'])) {
    $idAbsence = (int)$_POST['idAbsence'];
    $message = trim($_POST['message']);
    $fichier = $_FILES['justificatif'];

    if ($fichier['error'] === UPLOAD_ERR_OK) {
        $nomTemp = $fichier['tmp_name'];
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($extension, $extensionsAutorisees)) {

            $timestamp = time();
            $nomFinal = 'justificatif_' . $timestamp . '_' . $cinEtudiant . '_' . $idAbsence . '.' . $extension;

            $dossier = dirname(__DIR__, 4) . '/uploads/justificatif/';
            if (!is_dir($dossier)) {
                mkdir($dossier, 0777, true);
            }

            if (move_uploaded_file($nomTemp, $dossier . $nomFinal)) {
                $stmt = $pdo->prepare("
                    INSERT INTO Justificatif (dateSoumission, statut, message, idAbsence, nomFichierJustificatif)
                    VALUES (NOW(), 'en attente', :message, :idAbsence, :nomFichier)
                ");
                $stmt->execute([
                    'message' => $message,
                    'idAbsence' => $idAbsence,
                    'nomFichier' => $nomFinal
                ]);
                Logger::log("Soumission de justificatifs", 1, "UPLOAD", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
                header('Location: ' . $router->url('etudiant-messagerie'));
                exit();

            } else {
                echo "<script>alert('Erreur lors de l envoi du fichier.');</script>";
            }
        } else {
            echo "<script>alert('Extension non autorisée. Fichiers acceptés : jpg, jpeg, png, pdf');</script>";
        }
    } else {
        echo "<script>alert('Erreur lors du téléchargement du fichier.');</script>";
    }
}
