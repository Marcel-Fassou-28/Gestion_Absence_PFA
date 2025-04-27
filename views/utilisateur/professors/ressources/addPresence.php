<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if(isset($_SESSION['id_user']) && $_SESSION['role'] != 'professeur') {
    header('location: ' . $router->url('user-dashboard' , ['role' => $_SESSION['role']]));
    exit();
}


use App\Connection;
use App\Professeur\CurrentInfo;
use App\Professeur\ProfessorTable;
use App\Model\ListePresence;

$cinProf = $_SESSION['id_user'];
$pdo = Connection::getPDO();
$tableProf = new ProfessorTable($pdo);
$tableProfCurrent = new CurrentInfo($pdo);

$creneau = $tableProfCurrent->getCurrentCreneau($cinProf);
$error = null;
if (!isset($creneau)) {
    $error = 1;
    header('location: '. $router->url('user-dashboard', ['role' => $_SESSION['role']]) .'?error_prof=' . $error);
    exit();
}

if ($tableProfCurrent->hasAlreadySendListPresence($cinProf)) {
    $error_presence = 1;
    header('location: ' . $router->url('user-dashboard', ['role' => $_SESSION['role']]) . '?error_presence_file='. $error);
    exit();
}


$classe = $tableProfCurrent->getCurrentClasse($cinProf)->getNomClasse();
$matiere = $tableProfCurrent->getCurrentMatiere($cinProf)->getNomMatiere();
$listPresence = new ListePresence();
$success = null;

if (!empty($_POST)) {

    $tmpName = $_FILES['absence-list']['tmp_name'];
    $fileSize = $_FILES['absence-list']['size'];

    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'heic'];
    $extension = strtolower(pathinfo($_FILES['absence-list']['name'], PATHINFO_EXTENSION));

    if (in_array($extension, $extensionsAutorisees) && $fileSize <= 5000000) {

        $nouveauNom = time().'_'.uniqid('presence', true). '.' . $extension;
        $destination = dirname(__DIR__, 4) .DIRECTORY_SEPARATOR. 'uploads/presence/' . $nouveauNom;

        /**
        * Definition de la liste d'objet 
        */
        $listPresence->setNomFichierPresence($nouveauNom);
        $listPresence->setCINProf($cinProf);
        $listPresence->setClasse($classe);
        $listPresence->setMatiere($matiere);

        if(move_uploaded_file($tmpName, $destination) && $tableProf->sendListPresence($listPresence)) {
            $success = 1;
            header('location: '. $router->url('professor-listePresence') . '?use-link=student-presence' . '?success='.$success);
            exit();
        } else {
            $success = 0;
            $errorMessage = "Erreur lors de l'envoi.";
        }
    } else {
        $success = 0;
        $errorMessage = "Fichier invalide : extension non autorisée ou taille > 5 Mo.";
    }
}

function isDesktop() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Liste simple de mots-clés associés aux appareils mobiles/tablettes
    $mobileAgents = ['Mobile', 'Android', 'Silk/', 'Kindle', 'BlackBerry', 'Opera Mini', 'Opera Mobi', 'iPhone', 'iPod', 'iPad'];
    foreach ($mobileAgents as $device) {
        if (stripos($userAgent, $device) !== false) {
            return false; // Ce n'est pas un PC
        }
    }
    return true; // Aucun mot-clé mobile détecté → probablement un PC
}


?>

<!-- Boîte modale pour le scanner -->
<div class="modal-box" id="scanner-modal">
<?php if (isset($_GET['should_submit']) && $_GET['should_submit'] == 1): ?>
        <div class="alert alert-warning">
            Vous devez soumettre une liste de présence
    <?php endif ?>
        <div class="modal-box-overlay"></div>
        <form class="modal-box-content" method="post" action="<?= $router->url('add-presence') . '?use-link=student-presence' . '?redirect=1' ?>" enctype="multipart/form-data">
            <h2>Liste de présence</h2>
            <?php if ($success === 0): ?>
                <div><?= $errorMessage?></div>
            <?php endif ?>
            <?php if(isDesktop()): ?> 
            <div class="upload-desktop">
                <label for="presence-file">Uploader un fichier :</label>
                <input type="file" id="presence-file" name="absence-list" accept="image/*" required>
            </div>
            <?php else : ?>
            <div id="upload-mobile">
                <label for="presence-camera">Prendre une photo :</label>
                <input type="file" id="presence-camera" name="absence-list" accept="image/*" capture="environment" required>
            </div>
            <?php endif ?>
            <div class="modal-buttons">
                <button type="button" onclick="window.location.href='<?= $router->url('professor-listePresence') . '?use-link=student-presence'?>'" class="btn-modal close-modal">Annuler</button>
                <button name="submit" type="submit" class="btn-modal submit-presence">Valider</button>
            </div>
        </form>
    </div>

<script>
document.getElementById('presence-file').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) { // Limite à 5MB
            alert('L\'image ne doit pas dépasser 5 Mo.');
            event.target.value = '';
            return;
        }
    }
});

document.getElementById('presence-camera').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) { // Limite à 5MB
            alert('L\'image ne doit pas dépasser 5 Mo.');
            event.target.value = '';
            return;
        }
    }
});


</script>