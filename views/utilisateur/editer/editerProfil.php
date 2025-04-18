<?php 

use App\Connection;
use App\UserTable;



if (isset($_SESSION)) {
    $pdo = Connection::getPDO();
    $table = new UserTable($pdo);
    $user = $table->getIdentification($_SESSION['id_user']);

}

?>


<div class="container edit-profil-interface">
    <div class="edit-image-section">
        <img src="/images/profil.jpg" alt="Photo de profil de <?= htmlspecialchars($user->getNom()) ?>">
        <h3><?= htmlspecialchars($user->getRole() . ' ' . $user->getNom()) ?></h3>
    </div>
    <form class="edit-container-useful" method="post" action="" enctype="multipart/form-data">
        <div class="edit-info-section">
            <h2>Vos Informations</h2>
            <div class="edit-personal-info">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" >
                </div>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" name="username">
                </div>
            </div>
        </div>
        <div class="change-profil-photo">
            <h3>User Profil</h3>
            <div class="current-photo">
                <p>Current photo : </p>
                <div>
                    <img src="/images/profil.jpg" alt="Photo de profil de <?= htmlspecialchars($user->getNom()) ?>">
                    <div>
                        <input type="checkbox" name="delete-image" id="check-delete">
                        <label for="check-delete">Supprimer L'image</label>
                    </div>
                </div>
            </div>
            <div class="add-new-photo">
                <p>New photo : </p>
                <div>
                    <input type="file" name="new-photo-profil" id="">
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="update-profil">Mettre à Jour</button>
            <button type="button" class="cancel-profil">Annuler</button>
        </div>
    </form>
</div>