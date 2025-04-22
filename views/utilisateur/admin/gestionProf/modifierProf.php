<?php

if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Admin\adminTable;
use App\Professeur\ProfessorTable;
use App\connection;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
$gen = new professorTable($pdo);
if (!empty($_GET) && isset($_GET['modifier'])) {
    $cin = $_GET['cin'];

    $prof = $result->getProfByCin($cin);
    $mat = $gen->getMatiere($cin);
    $reste = $result->getAll("matiere", 'classMatiere');

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
                        
                        
                        <?php $i =0; foreach($mat as $m): ?>
                            <label for="matiere">Matiere<?=++$i?></label><br>
                            <select name="Mat[]" id="">
                                <option value="<?= $m->getNomMatiere();?>" ><?= $m->getNomMatiere();?></option>
                                <?php foreach ($reste as $mats):
                                    if ($mats->getNomMatiere() !== $m->getNomMatiere() ):
                                    ?>
                                    
                                    <option value="<?= $mats->getNomMatiere();?>" ><?= $mats->getNomMatiere();?></option>
                                    <?php endif;
                                    endforeach?>


                            </select><br>
                        <?php endforeach?>
                    </div>
                    
                    <input type="submit" class="btn" value="Sauvegarder">
                </form>
            </div>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        var_dump($_POST);
        $username = $_POST['username'];
        $newcin = $_POST['cin'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $oldCin = $prof[0]->getCIN();
        if ($result->ModifierProf($username, $newcin, $nom, $prenom, $email, $oldCin)) {
            //header('Location: liste-des-professeurs?listprof=1');
            //exit;
        } else {
            echo "Erreur lors de la modification du professeur.";
        }
    }


} else {
    $cin = $_GET['cin'];
    if ($result->SuprimerProf($cin)) {
        header('location: liste-des-professeurs?listprof=1');
        exit;
    } else {
        echo 'Erreur lors de la suppression';
    }
}
?>