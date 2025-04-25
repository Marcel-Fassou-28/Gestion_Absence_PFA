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

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$line = 20;
$offset = $_GET['p'] * $line;

$pdo = Connection::getPDO();
$tableAdmin = new adminTable($pdo);

$listeFichierPresence = $tableAdmin->getAllFichierListPresence($line, $offset);

//$listeClasse = $tableAdmin->getAllClasse();
if (isset($_POST['submit'])) {
    $classe = $_POST['classe'] ?? '';
    $matiere = $_POST['matiere'] ?? '';

    if(!empty($classe)) {
        $listeFichierPresence = $tableAdmin->getAllFichierListPresenceByClasse($classe, $line, $offset);
    }elseif(!empty($classe) && !empty($matiere) ) {
        $listeFichierPresence = $tableAdmin->getAllFichierListPresenceByClasseMatiere($classe, $matiere, $line, $offset);
    }
}
$n = count($listeFichierPresence);
?>


<div class="prof-list">
    <?php if (isset($_GET['success_absence']) && $_GET['success_absence'] == '1'): ?>
       <div class="alert alert-success">
        Liste de presence prise en condération avec succès
       </div>
    <?php endif ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
       <div class="alert alert-success">
        Liste de presence supprimer avec succès
       </div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div class="alert alert-danger">
        Votre opération n'a pas etre effectuée
       </div>
        <?php else: ?><?php endif ?>
    <div class="intro-prof-list">
        <h1>Fichiers de Listes de Presence</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
    <form action="" class="tri-list container" method="POST">
        <div class="list-classe">
            <select name="classe" id="tri-classe">
                <option value="">Classe</option>
                <!-- Ensemble des classes tiré de la base de données -- -->
            </select>
        </div>
        <div class="list-classe">
            <select name="matiere" id="tri-matiere">
                <option value="">Matiere</option>
            </select>
        </div>
        <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>
        </form>
    </div>
    <div class="list-tri-table-justificatif">
            <table>
                <thead>
                    <th>N°</th>
                    <th>Nom et Prenom du Professeur</th>
                    <th>Date de soumission</th>
                    <th>Classe</th>
                    <th>Action</th>
                </thead>
                <?php $numero = 1 ?>
                <?php foreach ($listeFichierPresence as $row) { ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($row->getNomPrenom()) ?></td>
                        <td><?= htmlspecialchars($row->getDate()) ?></td>
                        <td><?= htmlspecialchars($row->getClasse()) ?></td>
                        <td>
                            <a class="btn1" href="<?= $router->url('liste-presence-soumis-details') . '?file='. $row->getNomFichierPresence() . '&listprof=1&p=0' ?>">Voir details</a>
                            <a class="btn2" id="delete" href="<?= $router->url('liste-presence-soumis-delete'). '?file='. $row->getNomFichierPresence() . '&listprof=1&p=0'?>">Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php
    // variable pour compter le nombre de page 
    //pour aficher le nombre total de page avec ou sans tri 
    $nbrpage = ceil($n / $line);
    //boucle d'affichage des numero de page 
    for ($i = 0; $i < $nbrpage; ) { ?>

        <a href="?<?= $tableAdmin->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a><?php
    }
    ?>
    </div>
</div>

<script>

    const apiUrl = "<?= $router->url('api-liste-classe')?>";
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {

        const classeSelect = document.querySelector('#tri-classe');
        const matiereSelect = document.querySelector('#tri-matiere');

        const classesData = {};
        data.forEach(classe => {
        const option = document.createElement('option');
        option.value = classe.nomClasse;
        option.textContent = classe.nomClasse;
        classeSelect.appendChild(option);

        classesData[classe.nomClasse] = classe.matieres;
        });

        classeSelect.addEventListener('change', function () {
        const selectedId = this.value;
        matiereSelect.innerHTML = '<option value="">Matiere</option>';

        if (selectedId && classesData[selectedId]) {
            matiereSelect.disabled = false;
            classesData[selectedId].forEach(matiere => {
            const option = document.createElement('option');
            option.value = matiere.nomMatiere;
            option.textContent = matiere.nomMatiere;
            matiereSelect.appendChild(option);
            });
        } else {
            matiereSelect.disabled = true;
        }
        });
    })
    .catch(error => console.error('Erreur de chargement des classes/matières :', error));
</script>
