<?php
if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Admin\adminTable;
use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

//variable pour la gestion de la pagination
$line = 20;
$offset = $_GET['p'] * $line;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

// utilisation de la variable de get pour gerer la pagination lors du tri 
if (isset($_GET['departement']) && !isset($_POST['departement']) && empty($_POST)) {
    $_POST['departement'] = $_GET['departement'];
}
if (isset($_GET['filiere']) && !isset($_POST['filiere']) && empty($_POST)) {
    $_POST['filiere'] = $_GET['filiere'];
}
if (isset($_GET['classe']) && !isset($_POST['classe']) && empty($_POST)) {
    $_POST['classe'] = $_GET['classe'];
}
//affichage des prof sans tri
$listeProf = $list->getAll("professeur", "classProf", $line, $offset);
$n = count($list->getAll("professeur", "classProf"));



// tri si le departement  est choisit
if (isset($_POST['departement'])) {
    if ($_POST['departement'] !== 'defaut') {
        $departement = $_POST['departement'];

        $n = count($list->getprofByDepartement("departement"));
        $listeFiliere = $list->fieldsByDepartement($departement);
        $listeProf = $list->getprofByDepartement($departement, $line, $offset);
    }
    $_GET['departement'] = $_POST['departement'];
}

// tri si la filiere est choisit
if (isset($_POST['filiere'])) {
    if ($_POST['filiere'] !== 'defaut') {
        $filiere = $_POST['filiere'];

        $n = count($list->getProfByFiliere($filiere));
        $listeClasse = $list->classByFields($filiere);
        $listeProf = $list->getProfByFiliere($filiere, $line, $offset);
    }
    $_GET['filiere'] = $_POST['filiere'];
}
//tri si la classe est choisie
if (isset($_POST['classe'])) {
    if ($_POST['classe'] !== 'defaut') {
        $classe = $_POST['classe'];
        $n = count($list->getProfByClass($classe));
        $listeProf = $list->getProfByClass($classe, $line, $offset);
    }
    $_GET['classe'] = $_POST['classe'];
}
?>
<div class="prof-list">
    <?php if (isset($_GET['success_prof']) && $_GET['success_prof'] == '1'): ?>
        <div class="alert alert-success">Professeur ajouté avec succès</div>
    <?php elseif (isset($_GET['success_prof']) && $_GET['success_prof'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <?php if (isset($_GET['modifie_success']) && $_GET['modifie_success'] == '1'): ?>
        <div class="alert alert-success">Informations du professeur modifié avec succès</div>
    <?php elseif (isset($_GET['modifie_success']) && $_GET['modifie_success'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == '1'): ?>
        <div class="alert alert-success">Informations du professeur supprimé avec succès</div>
    <?php elseif (isset($_GET['delete_success']) && $_GET['delete_success'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <div class="intro-prof-list">
        <h1> Liste Des Professeurs</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('ajouterProf') . '?listprof=1&p=0&modifier=1'; ?>" class="btn-ajout">Ajouter un
                Professeur</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-departement">
                <select name="departement" id="tri-departement" onchange="this.form.submit()">
                    <option value="defaut">Département</option>
                    <?php
                    if (isset($departement)): ?>
                        <option value="<?= $departement; ?>" selected><?= $departement; ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="list-filiere">
                <select name="filiere" id="tri-filiere" onchange="this.form.submit()">
                    <option value="defaut">Filières</option>
                    <?php
                    if (isset($filiere)): ?>
                        <option value="<?= $filiere; ?>" selected><?= $filiere; ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="list-classe">
                <select name="classe" id="tri-classe">
                    <option value="defaut">Classe</option>
                    <?php
                    if (isset($classe)): ?>
                        <option value="<?= $classe; ?>" selected><?= $classe; ?></option>
                    <?php endif; ?>
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
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>CIN</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($listeProf as $row) { ?>
                <tr>
                    <td><?= ++$offset; ?></td>
                    <td><?= htmlspecialchars($row->getNom()) ?></td>
                    <td><?= htmlspecialchars($row->getPrenom()) ?></td>
                    <td><?= htmlspecialchars($row->getCIN()) ?></td>
                    <td><?= htmlspecialchars($row->getEmail()) ?></td>
                    <td class="btns">
                        <a href="<?= $router->url('modifier-professeur').'?listprof=1&p=0&modifier=1&cin='.$row->getCIN()?>" class="btn1">Modifier</a>
                        <a id="delete" href="<?= $router->url('supprimer-professeur').'?listprof=1&p=0&modifier=1&cin='.$row->getCIN()?>" class="btn2">Supprimer</a>
                        
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
    // variable pour compter le nombre de page 
    //pour aficher le nombre total de page avec ou sans tri 
    $nbrpage = ceil($n / $line);
    //boucle d'affichage des numero de page 
    for ($i = 0; $i < $nbrpage; ) { ?>

        <a href="?<?= $list->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a><?php
    }
    ?>
</div>
<script>
    const apiUrl = "<?= $router->url('api-liste-departement') ?>";
    document.addEventListener("DOMContentLoaded", () => {
        const departementSelect = document.querySelector("#tri-departement");
        const filiereSelect = document.querySelector('#tri-filiere');
        const classeSelect = document.querySelector('#tri-classe');

        fetch(apiUrl) // ← à adapter selon ton routeur
            .then(res => res.json())
            .then(data => {
                console.log(data);
                data.forEach(departement => {
                    const option = document.createElement("option");
                    option.value = departement.nomDepartement;
                    option.textContent = departement.nomDepartement;
                    departementSelect.appendChild(option);
                });

                // Changement de département → charger filières
                departementSelect.addEventListener("change", () => {
                    const nomDep = departementSelect.value;
                    filiereSelect.innerHTML = '<option value="defaut">Filières</option>';
                    classeSelect.innerHTML = '<option value="defaut">Classes</option>';
                    filiereSelect.disabled = true;
                    classeSelect.disabled = true;

                    if (nomDep) {
                        const selectedDep = data.find(dep => dep.nomDepartement == nomDep);
                        selectedDep.filieres.forEach(filiere => {
                            const option = document.createElement("option");
                            option.value = filiere.nomFiliere;
                            option.textContent = filiere.nomFiliere;
                            filiereSelect.appendChild(option);
                        });
                        filiereSelect.disabled = false;
                    }
                });

                // Changement de filière → charger classes
                filiereSelect.addEventListener("change", () => {
                    const nomDep = departementSelect.value;
                    const nomFiliere = filiereSelect.value;
                    classeSelect.innerHTML = '<option value="defaut">Classe</option>';
                    classeSelect.disabled = true;

                    if (nomDep && nomFiliere) {
                        const selectedDep = data.find(dep => dep.nomDepartement == nomDep);
                        const selectedFiliere = selectedDep.filieres.find(fil => fil.nomFiliere == nomFiliere);
                        selectedFiliere.classes.forEach(classe => {
                            const option = document.createElement("option");
                            option.value = classe.nomClasse;
                            option.textContent = classe.nomClasse;
                            classeSelect.appendChild(option);
                        });
                        classeSelect.disabled = false;
                    }
                });
            })
            .catch(err => {
                console.error("Erreur chargement données :", err);
            });
    });
</script>