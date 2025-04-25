<?php
use App\Connection;

$pdo = Connection::getPDO();
$cinEtudiant = $_SESSION['id_user'];

// Récupération de la date filtrée (date sélectionnée ou aujourd'hui par défaut)
$filtreDate = $_POST['filtre_date'] ?? date('Y-m-d');

// Récupération des absences filtrées par date
$stmt = $pdo->prepare("
    SELECT a.idAbsence, a.date, m.nomMatiere, j.statut, j.message, j.nomFichierJustificatif
    FROM Absence a
    JOIN Matiere m ON a.idMatiere = m.idMatiere
    LEFT JOIN Justificatif j ON a.idAbsence = j.idAbsence
    WHERE a.cinEtudiant = :cin
    AND DATE(a.date) = :filtreDate
    ORDER BY a.date DESC
");
$stmt->execute([
    'cin' => $cinEtudiant,
    'filtreDate' => $filtreDate
]);
$absences = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard-messagerie container">
    <div>
        <h2 class="messagerie-intro">Historique des absences</h2>
        <div class="hr"></div>
    </div>

    <!-- Formulaire de filtre par date -->
    <form method="POST" class="date-input-form">
    <input type="date" name="filtre_date" class="date-picker" value="<?= date('Y-m-d', strtotime($filtreDate)) ?>">
    <button type="submit" class="btn-filtrer">Filtrer</button>
    </form>


    <div class="dashboard-container">
        <table class="absence-table">
            <tr>
                <th>Date</th>
                <th>Module</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>

            <?php if (count($absences) === 0): ?>
                <tr>
                    <td colspan="4">Aucune absence trouvée pour cette date.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($absences as $absence): ?>
                <tr>
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
                            <button type="button" class="btn-soumettre" onclick="alert('Message: <?= htmlspecialchars($absence['message']) ?>')">Voir détails</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
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
        $nomFinal = uniqid() . '_' . basename($fichier['name']);

        //  Corrigé : chemin correct vers /uploads/justificatif/
        $dossier = dirname(__DIR__, 4) . '/uploads/justificatif/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }

        move_uploaded_file($nomTemp, $dossier . $nomFinal);

        $stmt = $pdo->prepare("
            INSERT INTO Justificatif (dateSoumission, statut, message, idAbsence, nomFichierJustificatif)
            VALUES (NOW(), 'en attente', :message, :idAbsence, :nomFichier)
        ");
        $stmt->execute([
            'message' => $message,
            'idAbsence' => $idAbsence,
            'nomFichier' => $nomFinal
        ]);

        //  Redirection pour éviter double soumission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;

    } else {
        echo "<script>alert('Erreur lors du téléchargement du fichier.');</script>";
    }
}

