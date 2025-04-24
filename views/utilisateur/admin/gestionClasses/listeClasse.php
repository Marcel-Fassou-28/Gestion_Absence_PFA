<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Admin\StatisticAdmin;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$adminTable = new StatisticAdmin($pdo);
$allClasse = $adminTable->getAllClasses();
$numero = 1;

if (!empty($_POST) && isset($_POST['submit-first'])) {
    $niveau = $_POST['niveau'] ?? '';
    $filiere = $_POST['filiere'] ?? '';

    $allClasse = $adminTable->getAllClassesByLevelFiliere($niveau, $filiere);
}
?>
<div class="prof-list">
    <?php if (isset($_GET['super_admin']) && $_GET['super_admin'] == '1'): ?>
        <div class="alert alert-danger">Impossible de supprimer cette classe</div>
    <?php endif ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success">Les Infos classe modifiée avec succès</div>
    <?php elseif(isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div class="alert alert-danger">Les Infos de classe n'ont pas pu être modifiée</div>
    <?php endif ?>

    <?php if (isset($_GET['success_delete']) && $_GET['success_delete'] == '1'): ?>
        <div class="alert alert-success">Classe supprimé avec succès</div>
        <?php elseif(isset($_GET['success_delete']) && $_GET['success_delete'] == '0'): ?>
            <div class="alert alert-danger">Aucun classe supprimer</div>
    <?php endif ?>

    <div class="intro-prof-matiere">
        <h1>Classes d'Enseignements</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-classe">
            <select name="niveau" id="tri-niveau">
                <option value="">Niveau</option>
                <!-- Niveau -->
            </select>
            </div>
            <div class="list-filiere">
            <select name="filiere" id="tri-filiere">
                <option value="">Filières</option>
                <!-- Filières -->
            </select>
            </div>
            <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit-first">
            </div>

        </form>
    </div>
    <div class="list-tri-table">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Filière</th>
                    <th>Classe</th>
                    <th>Niveau</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($allClasse as $classe): ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($classe->getNomFiliere()) ?></td>
                        <td><?= htmlspecialchars($classe->getNomClasse()) ?></td>
                        <td><?= htmlspecialchars($classe->getNomNiveau())?></td>
                        <td>
                            <a href="<?= $router->url('classe-modifie'). '?classe=1&p=0&id_classe='.$classe->getIDClasse() ?>" class="btn1">Modifier</a>
                            <a id="delete" href="<?= $router->url('classe-delete') .'?classe=1&p=0&id_classe='.$classe->getIDClasse() ?>" class="btn2">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>
</div>

<script>
    const apiUrl = "<?= $router->url('api-admin-niveau-filiere')?>";

    document.addEventListener("DOMContentLoaded", () => {
        const niveauSelect = document.querySelector('#tri-niveau');
        const filiereSelect = document.querySelector('#tri-filiere');

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                
                data.forEach(niveau => {
                    const option = document.createElement("option");
                    option.value = niveau.nomNiveau;
                    option.textContent = niveau.nomNiveau;

                    niveauSelect.appendChild(option);
                });

                niveauSelect.addEventListener("change", () => {
                    const selectedName = niveauSelect.value;
                    filiereSelect.innerHTML = '<option value="">Niveau</option>';
                    filiereSelect.disabled = true;


                    if (selectedName) {
                        const niveaux = data.find(n => n.nomNiveau == selectedName);
                        niveaux.filieres.forEach(filiere => {
                            const option = document.createElement("option");
                            option.value = filiere.nomFiliere;
                            option.textContent = filiere.nomFiliere;

                            filiereSelect.appendChild(option);
                        });

                        filiereSelect.disabled = false;
                    }
                });

            })
            .catch(error => {
                console.error("Erreur chargement filières/niveau :", error);
            });
    });
</script>