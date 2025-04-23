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
use App\Admin\adminTable;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$adminTable = new adminTable($pdo);
$allCreneaux = $adminTable->getAllCreneaux();
$numero = 1;
?>
<div class="prof-list">

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success">Créneau modifié avec succès</div>
    <?php elseif(isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div class="alert alert-danger">Le créneau n'a pas pu être modifié</div>
    <?php endif ?>

    <?php if (isset($_GET['add']) && $_GET['add'] == '1'): ?>
        <div class="alert alert-success">Créneau ajouté avec succès</div>
        <?php elseif(isset($_GET['add']) && $_GET['add'] == '0'): ?>
            <div class="alert alert-danger">Aucun créneau ajouté</div>
    <?php endif ?>

    <?php if (isset($_GET['success_delete']) && $_GET['success_delete'] == '1'): ?>
        <div class="alert alert-success">Créneau supprimé avec succès</div>
        <?php elseif(isset($_GET['success_delete']) && $_GET['success_delete'] == '0'): ?>
            <div class="alert alert-danger">Aucun créneau supprimer</div>
    <?php endif ?>

    <div class="intro-prof-list">
        <h1> Liste Des Créneaux</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('ajouter-creneaux') . '?listprof=1' ?>" class="btn-ajout">Ajouter un Créneau</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-filiere">
            <select name="filiere" id="tri-filiere">
                <option value="">Filières</option>
                <!-- Filières -->
            </select>
            </div>
            <div class="list-classe">
            <select name="classe" id="tri-classe">
                <option value="">Classe</option>
                <!-- Classe -->
            </select>
            </div>
            <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>

        </form>
    </div>

    <div class="list-tri-table">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Créneau</th>
                    <th>Jour Semaine</th>
                    <th>Classe</th>
                    <th>Professeur en Charge</th>
                    <th>Matiere</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allCreneaux as $creneau): ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($creneau->getHeureDebut() . ' - ' . $creneau->getHeureFin()) ?></td>
                        <td><?= htmlspecialchars($creneau->getJourSemaine())?></td>
                        <td><?= htmlspecialchars($creneau->getNomClasse()) ?></td>
                        <td><?= htmlspecialchars($creneau->getNomProf() . ' ' . $creneau->getPrenomProf()) ?></td>
                        <td><?= htmlspecialchars($creneau->getNomMatiere()) ?></td>
                        <td>
                            <a href="<?= $router->url('modifier-creneaux'). '?listprof=1&p=0&id_creneau='.$creneau->getID() ?>" class="btn1">Modifier</a>
                            <a id="delete" href="<?= $router->url('supprimer-creneaux') .'?listprof=1&p=0&id_creneau='.$creneau->getID() ?>" class="btn2">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>
</div>

<script>
    const apiUrl = "<?= $router->url('api-liste-filiere')?>";
    document.addEventListener("DOMContentLoaded", () => {
        const filiereSelect = document.querySelector('#tri-filiere');
        const classeSelect = document.querySelector('#tri-classe');

        fetch(apiUrl) 
            .then(response => response.json())
            .then(data => {
                console.log(data);
                data.forEach(filiere => {
                    const option = document.createElement("option");
                    option.value = filiere.nomFiliere;
                    option.textContent = filiere.nomFiliere;
                    filiereSelect.appendChild(option);
                });

                filiereSelect.addEventListener("change", () => {
                    const selectedName = filiereSelect.value;
                    classeSelect.innerHTML = '<option value="">Classe</option>';
                    classeSelect.disabled = true;

                    if (selectedName) {
                        const filiere = data.find(f => f.nomFiliere == selectedName);
                        filiere.classes.forEach(classe => {
                            const option = document.createElement("option");
                            option.value = classe.nomClasse;
                            option.textContent = classe.nomClasse;
                            classeSelect.appendChild(option);
                        });
                        classeSelect.disabled = false;
                    }
                });
            })
            .catch(error => {
                console.error("Erreur chargement filières/classes :", error);
            });
    });
</script>
