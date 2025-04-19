<?php

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\adminTable;
use App\connection;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
?>
<div class="modif">
    <h2>Modifier les information puis valider</h2>
    <div class="modif-div">
        <form method="POST" class="div-form" action="">
            <div class="form-input">
                <label for="cin">CIN</label>
                <input type="text" name="cin" value="">
            </div>
            <div class="form-input">
                <label for="nom">Nom</label>
                <input type="text" name="nom" value="">
            </div>
            
            <div class="form-input">
                <label for="prenom">Prenom</label>
                <input type="text" name="prenom" value="">
            </div>
            <div class="form-input">
                <label for="cne">CNE</label>
                <input type="text" name="cne" value="">
            </div>
            <div class="form-input">
                <label for="email">Email</label>
                <input type="text" name="email" value="">
            </div>
            <div class="form-input">
                <label for="password">Password</label>
                <input type="text" name="password" value="">
            </div>
            <div class="form-input">
                <label for="classe">Idclasse</label>
                <input type="number" name="classe" value="">
            </div>
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </div>
</div>
<?php
if (!empty($_POST)) {
    $role = 'etudiant';
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $cne = $_POST['cne'];
    $idClasse = $_POST['classe'];
    $username = $cin . '.' . $nom;
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    if ($result->AddStudentUser($cin, $nom, $prenom,  $email, $username, $password, $cne, $idClasse, $role)) {
        header('Location: liste-Etudiants');
        exit;
    } else {
        echo "Erreur lors de l'ajout du professeur.";
    }

}