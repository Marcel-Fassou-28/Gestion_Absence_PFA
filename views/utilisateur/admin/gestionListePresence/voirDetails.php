<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Admin\adminTable;
use App\Model\ListePresence;

$pdo = Connection::getPDO();

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$filename = $_GET['file'];
$filePath = dirname(__DIR__, 4) .DIRECTORY_SEPARATOR. 'uploads'.DIRECTORY_SEPARATOR.'presence' . DIRECTORY_SEPARATOR . $filename;

$query = $pdo->prepare('SELECT lp.*, CONCAT(p.nom ," ", p.prenom) as nomPrenom FROM listepresence lp JOIN professeur p ON lp.cinProf = p.cinProf JOIN matiere m ON p.cinProf = m.cinProf WHERE nomFichierPresence =:file');
$query->execute(['file' => $filename]);
$query->setFetchMode(\PDO::FETCH_CLASS, ListePresence::class);
$result = $query->fetch();
?>


<div class="prof-list">
<div class="intro-prof-list">
        <h1> Details de la liste de Presence</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a target="_blank" href="<?= $router->url('ajouter-considerer') . '?listprof=1&file='.$filename ?>" class="btn-ajout">Considerer</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container-file">
        <section class="professor-info container">
            <div class="prof-group">
                <span><?= htmlspecialchars($result->getNomPrenom()) ?></span>
            </div>
            <div class="classe-group">
                <span><?= htmlspecialchars($result->getClasse()) ?></span>
            </div>
            <div class="classe-group">
                <span><?= htmlspecialchars($result->getMatiere()) ?></span>
            </div>
            <div class="dateprof-group">
                <span><?= htmlspecialchars($result->getDate()) ?></span>
            </div>
        </section>
        <div class="presence-file">
            <div class="presence-file-show">
                <img src="<?= $router->url('serve-presence') .'?file='.$filename?>" alt="photo-presence">
            </div>
        </div>
    </div>
</div>