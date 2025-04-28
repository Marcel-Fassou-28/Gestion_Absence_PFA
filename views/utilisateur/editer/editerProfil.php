<?php 

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

function generateEmailToken($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

use App\Connection;
use App\UserTable;
use App\Model\Utilisateur;
use App\Mailer;


$pdo = Connection::getPDO();
$userTable = new UserTable($pdo);
$mailer = new Mailer();

$cinUser = $_SESSION['id_user'];
$user = $userTable->getIdentification($cinUser);
$success = null;
$utilisateur = new Utilisateur();

if(!empty($_POST)) {
    $email = filter_var($_POST['email'] , FILTER_VALIDATE_EMAIL);
    $username = trim($_POST['username']);
    $checkImage = $_POST['delete-image'] ?? false;

    if($checkImage) {
        if (!empty($_FILES)) {
            $tmpName = $_FILES['photo-profil']['tmp_name'];
            $fileSize = $_FILES['photo-profil']['size'];
    
            $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'heic'];
            $extension = strtolower(pathinfo($_FILES['photo-profil']['name'], PATHINFO_EXTENSION));
    
            if (in_array($extension, $extensionsAutorisees) && $fileSize <= 2000000) {
    
                $nouveauNom =  time() . uniqid('_profil', true). $_SESSION['role'] . '_'. $cinUser. '.' . $extension;
                $destination = dirname(__DIR__, 3) .DIRECTORY_SEPARATOR.'uploads' .DIRECTORY_SEPARATOR . 'profil' . DIRECTORY_SEPARATOR . $nouveauNom;

                $photoPath = $user->getNomPhoto() ?: 'avatar.png'; // Photo actuelle ou par défaut
                $destinationDir = dirname(__DIR__, 3) .DIRECTORY_SEPARATOR. 'uploads'.DIRECTORY_SEPARATOR.'profil' . DIRECTORY_SEPARATOR;
    
                if(move_uploaded_file($tmpName, $destination)) {
                    if ($photoPath != 'avatar.png' && file_exists($destinationDir . $photoPath)) {
                        unlink($destinationDir . $photoPath);
                    }

                    $query = $pdo->prepare('UPDATE utilisateur SET nomPhoto= :nomPhoto WHERE cin= :cin');
                    $query->execute(['cin' => $user->getCIN(), 'nomPhoto' => $nouveauNom]);
                    $success = 1;
                } else {
                    $success = 0;
                    $errorMessage = "Erreur lors de l'envoi.";
                }
            } else {
                $errorMessage = "Fichier invalide : extension non autorisée ou taille > 2 Mo.";
            }
        }
    }

    if ($email !== $user->getEmail()) {
        $token = generateEmailToken();
        $confirmLink = $router->url('verify-email') . '?token='. urlencode($token) . '&email='. urlencode($email);
        if ($mailer->emailChangeMail($email, $user->getNom() . ' ' . $user->getPrenom(), $confirmLink)) {
            $pdo->prepare("UPDATE utilisateur SET email = :email WHERE cin= :cin");
            $pdo->execute(['email' => $email, 'cin' => $user->getCIN()]);

            $pdo->exec("INSERT INTO utilisateur (token) VALUES ($token)");
        }
    }
    $userTable->updateUserInformation($username, $cinUser) ? $success = 1 : $success = 0;
    header('location: '. $router->url('user-profil', ['role'=> $_SESSION['role']]).'?user='.$_SESSION['role'] . '?success='. $success);
    exit();
}

?>

<div class="container edit-profil-interface">
    <div class="edit-image-section">
        <img src="<?= $router->url('serve-photo', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]) ?>" alt="Photo de profil de <?= htmlspecialchars($user->getNom()) ?>">
        <h3><?= htmlspecialchars($user->getRole() . ' ' . $user->getNom()) ?></h3>
    </div>
    <form class="edit-container-useful" method="post" action="" enctype="multipart/form-data">
        <section class="edit-info-section">
            <h2>Vos Informations</h2>
            <div class="edit-personal-info">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" required>
                </div>
            </div>
        </section>
        <section class="change-profil-photo">
            <h2>Photo de Profil</h2>
            <div class="current-photo">
                <p>Photo actuelle :</p>
                <div class="photo-actions">
                    <img src="<?= $router->url('serve-photo', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]) ?>" alt="Photo de profil actuelle de <?= htmlspecialchars($user->getNom()) ?>">
                    <div class="delete-photo">
                        <input type="checkbox" id="delete-image" name="delete-image">
                        <label for="delete-image">Supprimer l'image</label>
                    </div>
                </div>
            </div>
            <div class="add-new-photo">
                <p>Nouvelle photo :</p>
                <input type="file" name="photo-profil" id="new-photo-profil" accept="image/*" >
            </div>
        </section>
        <div class="form-actions">
            <button type="submit" class="update-profil">Mettre à Jour</button>
            <button type="button" class="cancel-profil" onclick="window.location.href='<?=$router->url('user-profil', ['role'=> $_SESSION['role']]). '?user='.$_SESSION['role'] ?>'">Annuler</button>
        </div>
    </form>
</div>