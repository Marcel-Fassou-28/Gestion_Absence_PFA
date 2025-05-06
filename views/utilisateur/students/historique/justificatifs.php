<?php
use App\Connection;
use App\Logger;

$pdo = Connection::getPDO();
$cinEtudiant = $_SESSION['id_user'];
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateDebut = $_POST['date_debut'];
    $dateFin = $_POST['date_fin'];
    $texteMessage = trim($_POST['message']);
    $fichier = $_FILES['justificatif'];

    if ($fichier['error'] === UPLOAD_ERR_OK) {
        $nomTemp = $fichier['tmp_name'];
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($extension, $extensionsAutorisees)) {
            $timestamp = time();
            $nomFinal = 'justificatif_' . $timestamp . '_' . $cinEtudiant . '.' . $extension;
            $cheminDossier = dirname(__DIR__, 4) . '/uploads/justificatif/';

            if (!is_dir($cheminDossier)) {
                mkdir($cheminDossier, 0777, true);
            }

            if (move_uploaded_file($nomTemp, $cheminDossier . $nomFinal)) {
                // Récupérer toutes les absences de l'étudiant entre les deux dates
                $stmt = $pdo->prepare("
                    SELECT idAbsence FROM Absence 
                    WHERE cinEtudiant = :cin 
                    AND DATE(date) BETWEEN :debut AND :fin
                ");
                $stmt->execute([
                    'cin' => $cinEtudiant,
                    'debut' => $dateDebut,
                    'fin' => $dateFin
                ]);
                $absences = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($absences) {
                    foreach ($absences as $absence) {
                        $stmtInsert = $pdo->prepare("
                            INSERT INTO Justificatif (dateSoumission, statut, message, idAbsence, nomFichierJustificatif)
                            VALUES (NOW(), 'en attente', :message, :idAbsence, :nomFichier)
                        ");
                        $stmtInsert->execute([
                            'message' => $texteMessage,
                            'idAbsence' => $absence['idAbsence'],
                            'nomFichier' => $nomFinal
                        ]);
                    }

                    Logger::log("Soumission globale de justificatifs", 1, "UPLOAD", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
                    $message = "Justificatif soumis pour " . count($absences) . " absence(s).";
                    $success = true;
                } else {
                    $message = "Aucune absence trouvée entre les dates indiquées.";
                    $success = false;
                }
            } else {
                $message = "Erreur lors de l'envoi du fichier.";
            }
        } else {
            $message = "Extension non autorisée. Fichiers acceptés : jpg, jpeg, png, pdf.";
        }
    } else {
        $message = "Erreur lors du téléchargement du fichier.";
    }
}
?>

<div class="prof-list">
    <div class="intro-prof-list">
        <h1>Soumettre un justificatif</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>

<?php if ($message): ?>
    <p style="color: <?= $success ? 'green' : 'red' ?>"><?= $message ?></p>
<?php endif; ?>
<div class="list-tri-table">
    
<form method="POST" enctype="multipart/form-data" class="justif-form">
    <div class="form-groupe">
        <label for="date_debut">Date début :</label>
        <input type="date" name="date_debut" required>
    </div>

    <div class="form-groupe">
        <label for="date_fin">Date fin :</label>
        <input type="date" name="date_fin" required>
    </div>

    <div class="form-groupe">
        <label for="message">Message :</label>
        <input type="text" name="message" placeholder="Ex : certificat médical" required>
    </div>

    <div class="form-groupe">
        <label for="justificatif">Fichier justificatif :</label>
        <input type="file" name="justificatif" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>

        <button type="submit" class="btn1">Soumettre</button>
    </form></div>
</div>
