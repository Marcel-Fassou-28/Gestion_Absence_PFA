<?php

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_mail'])) {
    header('location: ' .$router->url('page-connexion'));
    exit();
}

use App\Connection;
use App\UserTable;

$pdo = Connection::getPDO();
$error = false;

$secretKey = getenv('SECRET_KEY');

/**
 * On peut le faire avec une clé sécrete (hash_hmac)
 * 
 */
function encodindCIN($cin) {
    global $secretKey;
    return  hash_hmac('sha256', $cin, $secretKey);
}

if (!empty($_POST)) {
    $code = $_POST['code-recovery'];
    $codeSanitized = filter_var($code, FILTER_SANITIZE_NUMBER_INT);

    if (!empty($code) && filter_var($codeSanitized, FILTER_VALIDATE_INT)) {
        $code = $codeSanitized;
        $table = new UserTable($pdo);
        $user = $table->findByEmail($_SESSION['user_mail']);

        if ($user !== null) {
            if($code === $user->getCodeRecuperation()) {
                header('Location:' . $router->url('password-recovery', ['id' => encodindCIN($user->getCIN())]));
                exit();
            } else {
                $error = true;
            }
        }
    }
}
?>

<div class="login-section">
    <div class="login-container">
        <h2>Recuperation de Compte</h2>
        <?php if ($error): ?>
            <p>Le code de vérification saisi est incorrect. Veuillez vérifier et réessayer, ou demandez un nouveau code si vous ne l'avez pas reçu.</p>
        <?php else: ?>
        <p>Saississez le code d'Authentification reçue par email</p>
        <?php endif ?>
        <form method="post" action="">
            <div class="input-field">
                <input type="number" name="code-recovery" placeholder="Code reçu par email" required>
                <i class="fas fa-lock"></i>
            </div>
            <input type="submit" class="login-btn" value="Envoyer">
        </form>
    </div>
</div>