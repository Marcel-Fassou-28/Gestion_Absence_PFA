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
use App\Mailer;


$secretKey = getenv('SECRET_KEY');

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

$pdo = Connection::getPDO();
$error = false;

if(!empty($_POST)) {
    $newPassword = trim($_POST['new_password']) ?? '';
    $confirmPassword = trim($_POST['confirm_password']) ?? '';

    if(!empty($newPassword) && !empty($confirmPassword) && $newPassword === $confirmPassword) {
        $table = new UserTable($pdo);
        $mailer = new Mailer();

        $email = $_SESSION['user_mail'];
        $user = $table->findByEmail($email);

        $dateCreation = new DateTime($user->getDateDerniereReinitialisation());
        $maintenant = new DateTime();
        $interval = $maintenant->getTimestamp() - $dateCreation->getTimestamp();
        if ($interval > 900) {
            $table->codeReset($email);
            session_unset();
            session_destroy();
            header('Location:' . $router->url('page-connexion') .'?error=1');
            exit();

        } else {
            $status = $table->changePassword($newPassword, $email);
            if($status) {
                session_unset();
                $_SESSION['id_user'] = $user->getCIN();
                $_SESSION['role'] = $user->getRole();
                $_SESSION['username'] = pascalCase($user->getNom() . ' ' .$user->getPrenom());

                $mailer->passwordChangedMail($user->getEmail(), $user->getNom() . ' ' .$user->getPrenom());
                header('Location:' . $router->url('user-home', ['role' =>$user->getRole(), 'id' => encodindCIN($user->getCIN())]));
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
        <h2>Changer votre mot de passe</h2>

        <?php if($error): ?>
            <p style="color: red;">Vous devez utiliser un autre mot de passe</p>
        <?php endif ?>
        
        <form method="post" action="">
        <div class="input-field">
            <input type="password" id="new-password" name="new_password" placeholder="Nouveau Mot de passe" required>
            <i class="fas fa-lock"></i>
        </div>
        <div class="input-field">
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirmer Mot de passe" required>
            <i class="fas fa-lock"></i>
        </div>
        <div id="password-error" style="color: red; font-size: 14px; margin-top: 5px;"></div>
        <button type="submit" id="submit-button" class="login-btn" disabled>Réinitialiser</button>
        </form>
    </div>
</div>

<script>
    // Sélectionner les éléments
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    const errorDiv = document.getElementById('password-error');
    const submitButton = document.getElementById('submit-button');

    function getStringLength(str) {
        return str.length;
    }

    // Fonction pour vérifier la correspondance
    function checkPasswords() {
        const newValue = newPassword.value;
        const confirmValue = confirmPassword.value;

        if (newValue === '' || confirmValue === '') {
            errorDiv.textContent = '';
            submitButton.disabled = true;
        } else if (newValue !== confirmValue) {
            errorDiv.textContent = 'Les mots de passe ne correspondent pas.';
            submitButton.disabled = true;
        }else if(getStringLength(newPassword) < 8) {
            errorDiv.textContent = 'Le nombre de caractère inférieur à 8.';
            submitButton.disabled = true;
        } else {
            errorDiv.textContent = '';
            submitButton.disabled = false;
        }
    }

    // Écouter les changements dans les deux champs
    newPassword.addEventListener('input', checkPasswords);
    confirmPassword.addEventListener('input', checkPasswords);
</script>