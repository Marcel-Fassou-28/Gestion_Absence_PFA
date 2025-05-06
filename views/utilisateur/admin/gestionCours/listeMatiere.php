<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

$line = 20;
$offset = $_GET['p'] * $line;
$title = "Administration";
use App\Connection;
use App\Admin\StatisticAdmin;

$pdo = Connection::getPDO();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$adminTable = new StatisticAdmin($pdo);
$allMatiere= $adminTable->getAllMatiere();
$numero = 1;

if(!empty($_POST) && $_POST['submit-first'] == 'Trier') {
    $filieres = $_POST['filiere'] ?? '';
    $classes = $_POST['classe'] ?? '';

    if($filieres != '') {
        $allMatiere = $adminTable->getAllMatiereByFilieres($filieres, $line, $offset);

    } elseif($filieres != '' && $classes != '') {
        $allMatiere = $adminTable->getAllMatiereByFilieresClasses($filieres, $classes, $line, $offset);
    }
}

$n = count($allMatiere);
?>
<div class="prof-list">

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success">Matière modifiée avec succès</div>
    <?php elseif(isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div class="alert alert-danger">La matière n'a pas pu être modifiée</div>
    <?php endif ?>

    <?php if (isset($_GET['add']) && $_GET['add'] == '1'): ?>
        <div class="alert alert-success">Matière ajoutée avec succès</div>
        <?php elseif(isset($_GET['add']) && $_GET['add'] == '0'): ?>
            <div class="alert alert-danger">Aucune Matière ajoutée</div>
    <?php endif ?>

    <?php if (isset($_GET['success_delete']) && $_GET['success_delete'] == '1'): ?>
        <div class="alert alert-success">Matière supprimée avec succès</div>
        <?php elseif(isset($_GET['success_delete']) && $_GET['success_delete'] == '0'): ?>
            <div class="alert alert-danger">Aucune Matière supprimer</div>
    <?php endif ?>

    <div class="intro-prof-matiere">
        <h1> Liste Des Matières</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('liste-matiere-ajouter') . '?matiere=1&p=0' ?>" class="btn-ajout">Ajouter une Matière</a>
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
            <input class="submit-btn" type="submit" value="Trier" name="submit-first">
            </div>

        </form>
    </div>

    <div class="list-tri-table">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Matiere</th>
                    <th>Classe</th>
                    <th>Professeur</th>
                    <th>Cin Prof</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($allMatiere as $matiere): ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($matiere->getNomMatiere())?></td>
                        <td><?= htmlspecialchars($matiere->getNomClasse())?></td>
                        <td><?= htmlspecialchars($matiere->getNomProf() . ' ' . $matiere->getPrenomProf()) ?></td>
                        <td><?= htmlspecialchars($matiere->getCINProf()) ?></td>
                        <td>
                            <a href="<?= $router->url('liste-matiere-modifie'). '?matiere=1&p=0&id_matiere='.$matiere->getIdMatiere() ?>" class="btn1">Modifier</a>
                            <a id="delete" href="<?= $router->url('liste-matiere-delete') .'?matiere=1&p=0&id_matiere='.$matiere->getIdMatiere() ?>" class="btn2">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>
    <?php
    // variable pour compter le nombre de page 
    //pour aficher le nombre total de page avec ou sans tri 
    $nbrpage = ceil($n / $line);
    //boucle d'affichage des numero de page 
    for ($i = 0; $i < $nbrpage; ) { ?>

        <a href="?<?= $adminTable->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a><?php
    }
    ?>
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