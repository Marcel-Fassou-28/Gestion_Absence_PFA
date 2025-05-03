<?php
if (!isset($_SESSION['id_user'])) {
    header('Location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

$title = "Logs";

use App\LogManager;
use App\Connection;
use App\Model\Administrateur;
use App\Logger;

$pdo = Connection::getPDO();
$logManager = new LogManager();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$super_admin = null;

$levels = ['INFO', 'ERROR', 'SECURITY', 'DB', 'UPLOAD'];
$selectedLevel = $_POST['filiere'] ?? 'INFO';
$dateTrie = $_POST['date'] ?? date('Y-m-d');
$logs = $logManager->getLogsByDateAndLevel($selectedLevel, $dateTrie);

if (isset($_POST['clear-logs'])) {
    $query_verifie = $pdo->prepare('SELECT * FROM administrateur WHERE cinAdmin = :cinAdmin LIMIT 1');
    $query_verifie->execute(['cinAdmin' => $_SESSION['id_user']]);
    $query_verifie->setFetchMode(\PDO::FETCH_CLASS, Administrateur::class);
    $admin_verifie = $query_verifie->fetch();

    if ($admin_verifie && (string) $admin_verifie->getIDAdmin() == '1' && $admin != $_SESSION['id_user']) {
        $logManager->clearLogsFile($selectedLevel);
        Logger::log("Suppression du contenu du fichier de Log" . $selectedLevel, 1, "INFO", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
        $super_admin = 0;
    } else {
        $super_admin = 1;
    }
        
}
?>

<div class="prof-list">
   <div class="intro-prof-list">
        <h1>Consultation des Logs</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <?php if (isset($super_admin) && $super_admin == 1): ?>
            <div class="alert alert-danger">Vous devez etre le super administrateur pour effectuer cette opération</div>
        <?php elseif(isset($super_admin) && $super_admin == 0): ?>
            <div class="alert alert-success">Fichier de log supprimer avec succès</div>
        <?php endif ?>

    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-filiere">
                <select name="filiere" id="tri-filiere">
                    <?php foreach ($levels as $level): ?>
                        <option value="<?= strtolower($level) ?>" <?= $selectedLevel === $level ? 'selected' : '' ?>><?= ucfirst($level) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="date-group">
                <input type="date" name="date" id="date" value="<?= $dateTrie ?>">
            </div>
            <div class="submit-group">
                <input class="submit-btn" type="submit" value="Trier" name="submit-first">
                <button type="submit" name="clear-logs" class="clear-btn" value="clear-log">Effacer les logs</button>
            </div>

        </form>
    </div>
    <div class="list-tri-table">
    <?php if (empty($logs)): ?>
        <p>Aucun log disponible pour ce niveau ou cette date.</p>
    <?php else: ?>
        <pre>
            <?php
            $lines = explode("\n", htmlspecialchars($logs));
            foreach ($lines as $line):
                if (empty($line)) continue;
                // Déterminer le niveau du log à partir de la ligne (exemple : [INFO], [ERROR], etc.)
                preg_match('/\[([A-Z]+)\]/', $line, $matches);
                $logLevel = $matches[1] ?? $selectedLevel;
            ?>
                <span class="<?= htmlspecialchars($logLevel) ?>"><?= $line ?></span>
            <?php endforeach; ?>
        </pre>
    <?php endif; ?>
</div>
</div>

<style>

.date-group input[type="date"] {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 1rem;
    transition: border-color 0.3s;
    width: 100%;
}
/* Style pour le bouton Effacer les logs */
.submit-btn {
    width: 50%;
}
.submit-group .clear-btn {
    padding: 5px 15px;
    background-color: #e74c3c; /* Rouge pour indiquer une action destructive */
    color: #fff;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: background-color 0.3s;
    margin-left: 10px; /* Espacement avec le bouton Trier */
}

.submit-group .clear-btn:hover {
    background-color: #c0392b; /* Rouge plus foncé au survol */
}

/* Styles pour les logs */
.list-tri-table {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    max-height: 500px;
    overflow-y: auto;
}

.list-tri-table pre {
    white-space: pre-wrap;
    font-family: 'Courier New', monospace;
    font-size: 0.95rem;
    line-height: 1.8;
    margin: 0;
    color: #333;
}

/* Styles spécifiques par niveau de log */
.list-tri-table pre:where(.INFO) {
    color: #2ecc71;
}

.list-tri-table pre:where(.ERROR) {
    color: #e74c3c;
}

.list-tri-table pre:where(.SECURITY) {
    color: #e67e22;
}

.list-tri-table pre:where(.DB) {
    color: #9b59b6;
}

.list-tri-table pre:where(.UPLOAD) {
    color: #1abc9c;
}

/* Log lines styling */
.list-tri-table pre > span {
    display: block;
    padding: 8px;
    border-left: 4px solid transparent;
    transition: background-color 0.2s;
}

.list-tri-table pre > span:hover {
    background-color: var(--bg-section-abstract);
}

/* Styles pour chaque niveau dans les lignes */
.list-tri-table pre > span.INFO {
    border-left-color: #2ecc71;
}

.list-tri-table pre > span.ERROR {
    border-left-color: #e74c3c;
}

.list-tri-table pre > span.SECURITY {
    border-left-color: #e67e22;
}

.list-tri-table pre > span.DB {
    border-left-color: #9b59b6;
}

.list-tri-table pre > span.UPLOAD {
    border-left-color: #1abc9c;
}

/* Responsivité */
@media (max-width: 768px) {
    .date-group input[type="date"] {
        width: 100%;
    }
}
</style>
<script>
    document.querySelector('.clear-btn').addEventListener('click', function (event) {
        if (!confirm('Êtes-vous sûr de vouloir effacer les logs ? Cette action est irréversible.')) {
            event.preventDefault(); // Annuler la soumission si l'utilisateur clique sur "Annuler"
        }
    });
</script>