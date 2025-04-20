<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Admin\adminTable;
use App\connection;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
if (!empty($_GET) && isset($_GET['cin'])) {
    $cin = $_GET['cin'];

    $prof = $result->getProfByCin($cin);
    $student = $result->getStudentByCin($cin);

    ?>
    <div class="contains">
    <div class="modif">
        <h2>Modifier les information puis valider</h2>
        <div class="modif-div">
            <form method="POST" class="div-form" action="">
                <div class="form-input">
                    <label for="username">Username</label>
                    <input type="text" name="username" value="<?= $prof[0]->getUsername(); ?>">
                </div>
                <div class="form-input">
                    <label for="cin">CIN</label>
                    <input type="text" name="cin" value="<?= $prof[0]->getCIN(); ?>">
                </div>
                <div class="form-input">
                    <label for="cne">CNE</label>
                    <input type="text" name="cne" value="<?= $student[0]->getCNE(); ?>">
                </div>
                <div class="form-input">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" value="<?= $prof[0]->getNom(); ?>">
                </div>
                <div class="form-input">
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" value="<?= $prof[0]->getPrenom(); ?>">
                </div>
                <div class="form-input">
                    <label for="email">Email</label>
                    <input type="text" name="email" value="<?= $prof[0]->getEmail(); ?>">
                </div>
                <div class="form-input">
                    <label for="classe">idClasse</label>
                    <input type="text" name="classe" value="<?= $student[0]->getIdClasse(); ?>">
                </div>
                <input type="submit" class="btn" value="Sauvegarder">
            </form>
        </div>
    </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $newcin = $_POST['cin'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $oldCin = $prof[0]->getCIN();
        $cne = $_POST['cne'];
        $idClasse = $_POST['classe'];
        if ($result->ModifierStudent($newcin, $nom, $prenom, $email, $username, $cne,$idClasse, $oldCin)) {
            header('Location: liste-Etudiants?listprof=1');
            exit;
        } else {
            echo "Erreur lors de la modification du professeur.";
        }
    }


} else if (!empty($_POST) && isset($_POST['cin'])) {
    $cin = $_POST['cin'];
    if ($result->SuprimerStudent($cin)) {
        header('location: liste-Etudiants?listprof=1');
        exit;
    } else {
        echo 'Erreur lors de la suppression';
    }
}  
?>