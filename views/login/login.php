<?php 
if(isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\UserTable;
use App\Connection;
use App\Logger;

$secretKey = getenv('SECRET_KEY');
$logger = new Logger();

/**
 * On peut le faire avec une clé sécrete (hash_hmac)
 * 
 */
function encodindCIN($cin) {
    global $secretKey;
    return  hash_hmac('sha256', $cin, $secretKey);
}

function pascalCase($chaine) {
    $chaine = str_replace("-", " ", $chaine);
    $mots = explode(" ", $chaine);
    $mots = array_map('ucfirst', $mots);

    return implode("-", $mots);
}


$errorPassword = false;
$pdo = Connection::getPDO();

if (!empty($_POST)) {
    $username =  $_POST['identifiant'];
    $password =  $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $table = new UserTable($pdo);
        $user = $table->findByUsername($username);

        if (password_verify( $password, $user->getPassword()) === true){
            session_start();
            $_SESSION['id_user'] = $user->getCIN();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['username'] = pascalCase($user->getNom() . ' ' .$user->getPrenom());

            Logger::log("Connexion reussie", 1, "SECURITY", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);

            header('Location:' . $router->url('user-home', ['role' =>$user->getRole(), 'id' => encodindCIN($user->getCIN())]));
            exit();
        }else {
            $errorPassword = true;
            Logger::log("Échec de connexion", 1,  "SECURITY");
        }
    } 
}

?>
<div class="login-section">
    <div class="login-container">
        <h2>Se connecter à GAENSAJ</h2>
        <?php if(isset($_GET['error']) && $_GET['error'] == '1'): ?>
            Le code de vérification a expiré. Veuillez demander un nouveau code;
        <?php endif ?>
        <?php if($errorPassword): ?>
        <div class="alert">
            Identifiant ou mot de passe incorrect
        </div>
        <?php endif ?>
        <form method="post" action="">
            <div class="input-field">
                <input type="text" placeholder="Identifiant" name="identifiant" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Mot de passe" required>
                <i class="fas fa-lock"></i>
            </div>
            <a href="<?= $router->url('forget-password') ?>" class="forgot-password">Mot de passe oublié?</a>
            <input type="submit" class="login-btn" value="Connexion">
        </form>
    </div>
</div>