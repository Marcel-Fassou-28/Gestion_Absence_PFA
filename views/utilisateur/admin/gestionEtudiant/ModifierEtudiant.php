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
use App\Model\Classe;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$cinEtudiant = $_GET['cin'];
$student = $result->getStudentInfoByCIN($cinEtudiant);

$success_modifie = null;
$error = 0;

if (isset($cinEtudiant)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $newcin = $_POST['cin'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $oldCin = $student->getCIN();
        $cne = $_POST['cne'];
    
        $nomClasse = $_POST['classe'];
        $query = $pdo->prepare('SELECT * FROM classe WHERE nomClasse = :nomClasse');
        $query->execute(['nomClasse' => $nomClasse]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Classe::class);
        $output = $query->fetch();
    
        if ($output) {
            $idClasse = $output->getIDClasse();
    
            if ($result->ModifierStudent($newcin, $nom, $prenom, $email, $username, $cne,$idClasse, $oldCin)) {
                $success_modifie = 1;
            } else {
                $success_modifie = 0;
            }
            header('location: ' . $router->url('liste-etudiants').'?listprof=1&p=0&success_modifie='. $success_modifie);
            exit();
        } else {
            $error = 1;
        }  
    } 
} else {
    $success_modifie = 0;
    header('location: ' . $router->url('liste-etudiants').'?listprof=1&p=0&success_etudiant='. $success_modifie);
    exit();
}
 ?>

<div class="prof-list">
    <?php if ($error === 1): ?>
        <div class="alert alert-danger">
            Entrer un nom de classe valide comme IITE-1, CCN-1, ISIC-1, AP-1, etc...
        </div>
    <?php endif ?>
    <div class="intro-prof-list">
        <h1> Ajouter un Etudiant</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-modifie-container">
        <form action="" class="creneau-modifie container" method="POST">
            <section class="edit-creneau-section">
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($student->getUsername()) ?>">
                </div>
                <div>
                    <label for="cin">CIN</label>
                    <input type="text" name="cin" value="<?= htmlspecialchars($student->getCIN()) ?>" required>
                </div>
                <div>
                    <label for="cne">CNE</label>
                    <input type="text" name="cne" value="<?= htmlspecialchars($student->getCNE()) ?>" required>
                </div>
                <div>
                    <label for="classe">Classe</label>
                    <input type="text" name="classe" value="<?= htmlspecialchars($student->getNomClasse()) ?>" placeholder="Exemple: IITE-1" required>
                </div>
                <div>
                    <label for="nom">Nom</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($student->getNom()) ?>" required>
                </div>
                <div>
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($student->getPrenom()) ?>" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" name="email" value="<?= htmlspecialchars($student->getEmail()) ?>" required>
                </div>
            </section>
            <section class="submit-group-creneau">
                <button type="submit" class="submit-btn-creneau">Ajouter</button>
                <button class="btn2" onclick= "window.location.href='<?= $router->url('liste-etudiants').'?listprof=1&p=0' ?>'">Annuler</button>
            </section>

        </form>
    </div>
</div>
