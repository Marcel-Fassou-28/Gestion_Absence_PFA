<?php 

function encoderCNE(string $cne) {
    return base64_encode($cne);
}

function decoderCNE(string $cne_encode) {
    return base64_decode($cne_encode);
}

use App\Connection;
use App\UserTable;

$pdo = Connection::getPDO();
$table = 'etudiant';
$error = false;
$error_password = false;

if (!empty($_POST)) {
    if (!empty($_POST['identifiant']) && !empty($_POST['password'])) {
        $table = new UserTable(Connection::getPDO(), $table);
        try {
            $user = $table->findByCIN($_POST['identifiant']);
            if ($user) {
                if (password_verify( $_POST['password'], $user->getPassword()) === true) {
                    session_start();
                    $_SESSION['id_user'] = $user->getCNE();
                    $username = $user->getNom() . ' ' . $user->getPrenom();
                    $slug = str_replace(" ", "-", mb_strtolower($username));
                    header('Location:' . $router->url('login', ['slug' =>$slug, 'id' => encoderCNE($user->getCNE())]));
                    exit();
                } else {
                    $error_password = true;
                }
            }
        }catch (Exception $e) {
            echo "$e";
        }
    }
}
?>
<div class="login-container">
    <div class="login-box">
    <?php if($error_password): ?>
        <div class="alert alert-danger">
            Identifiant ou mot de passe incorrect
        </div>
    <?php endif ?>
        <h2>Se connecter à GAENSAJ</h2>
        <form method="post" action="">
            <div class="input-group">
                <input type="text" placeholder="Identifiant" name="identifiant" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Mot de passe" required>
                <i class="fas fa-lock"></i>
            </div>
            <a href=<?= $router->url('forget_password') ?> class="forgot-password">Mot de passe oublié?</a>
            <input type="submit" class="login-btn" value="Connexion">
        </form>
    </div>
</div>