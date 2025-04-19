<?php
use App\adminTable;
use App\connection;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);
?>
<div class="contains">
<div class="modif">
    <h2>Entrer les information puis valider</h2>
    <div class="modif-div">
        <form method="POST" class="div-form" action="">
            <div class="form-input">
                <label for="cin">CIN</label>
                <input type="text" name="cin" value="" required>
            </div>
            <div class="form-input">
                <label for="nom">Nom</label>
                <input type="text" name="nom" value="" required>
            </div>
            <div class="form-input">
                <label for="prenom">Prenom</label>
                <input type="text" name="prenom" value="" required>
            </div>
            <div class="form-input">
                <label for="email">Email</label>
                <input type="text" name="email" value="" required>
            </div>
            <div class="form-input">
                <label for="password">Password</label>
                <input type="text" name="password" value="" required>
            </div>
            <button type="submit" class="btn">Ajouter Prof</button>
        </form>
    </div>
</div>
</div>
<?php
if (!empty($_POST)) {
    $role = 'professeur';
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $username = $cin . '.' . $nom;
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    if ($result->AddProfUser($cin, $nom, $prenom, $email, $username, $password, $role)) {
        header('Location: liste-des-professeurs');
        exit;
    } else {
        echo "Erreur lors de l'ajout du professeur.";
    }

}