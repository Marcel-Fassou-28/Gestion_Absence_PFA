<?php

use App\Connection;
use App\UserTable;
use App\Mailer;
use App\Logger;

$pdo = Connection::getPDO();
$mailer = new Mailer();
$error = false;
$errorEmail = '';

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
    $email = $_POST['email-recovery'];
    $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!empty($email) && filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
        $email = $emailSanitized;
        $table = new UserTable($pdo);
        $user = $table->findByEmail($email);

        if ($user !== null) {
            $authCode = sprintf('%06d', random_int(0, 999999));
            $table->codeInsertion($authCode, $email);

            if ($mailer->resetPasswordMail($email, $authCode, $user->getNom() . ' '. $user->getPrenom())) {
                session_start();
                $_SESSION['user_mail'] = $user->getEmail();
                Logger::log("Demande de réinitialisation de mot de passe", 1, "SECURITY", $_SESSION['user_mail']);
                header('Location: ' . $router->url('code-recuperation', ['id' => encodindCIN($user->getCIN())]));
                exit();
            } else {
                $error = true;
                $errorEmail = "L'adresse email saisie n'est pas valide. Veuillez vérifier et réessayer avec une adresse correcte.";
            }
        }
    }else {
        $error = true;
        $errorEmail = "L'adresse email saisie n'est pas valide. Veuillez vérifier et réessayer avec une adresse correcte.";
    }
}
?>

<div class="login-section">
    <div class="login-container">
        <h2>Recuperation de Compte</h2>
        <?php if ($error === true): ?>
            <p><?= $errorEmail ?></p>
        <?php else: ?>
        <p>Vous avez oublier votre mot de passe? Pas de soucis, veuillez entrer votre email pour réinitialiser votre mot de passe</p>
        <?php endif ?>
        <form method="post" action="">
            <div class="input-field">
                <input type="email" name="email-recovery" placeholder="Votre email" required>
                <i class="fas fa-lock"></i>
            </div>
            <input type="submit" class="login-btn" value="Envoyer">
        </form>
    </div>
</div>
