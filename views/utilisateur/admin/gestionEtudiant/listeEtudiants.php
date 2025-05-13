<?php
if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}
$title = "Administration";
use App\Admin\adminTable;


use App\Connection;
use App\Model\Classe;
use App\Model\Etudiant;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);
//variable pour la gestion de la pagination
$line = 20;
$offset = $_GET['p'] * $line;
//variable n pour compter le nombre total de ligne extraite de la fonction getALL()
$n = count($list->getAll("etudiant", Classe::class));


$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');



// utilisation de la variable de get pour gerer la pagination lors du tri 
if (isset($_GET['filiere']) && !isset($_POST['filiere']) && empty($_POST)) {
    $_POST['filiere'] = $_GET['filiere'];
}
if (isset($_GET['classe']) && !isset($_POST['classe']) && empty($_POST)) {
    $_POST['classe'] = $_GET['classe'];
}
//affichage des etudiants sans tri
$listeEtudiant = $list->getAll("etudiant", Etudiant::class, $line, $offset);

// tri si la filiere est choisit
if (isset($_POST['filiere'])) {
    if ($_POST['filiere'] !== 'defaut') {
        $filiere = $_POST['filiere'];
        
        $n = count($list->getStudentByFiliere($filiere));
        $listeEtudiant = $list->getStudentByFiliere($filiere, $line, $offset);
    }
    $_GET['filiere'] = $_POST['filiere'];
}

// tri par classe si une classe est choisit
if (isset($_POST['classe'])) {
    if ($_POST['classe'] !== 'defaut') {
        $classe = $_POST['classe'];

        $n = count($list->getStudentByClass($classe));
        $listeEtudiant = $list->getStudentByClass($classe, $line, $offset);
    }
    $_GET['classe'] = $_POST['classe'];

}

?>
<div class="prof-list">
    <?php if (isset($_GET['success_etudiant']) && $_GET['success_etudiant'] == '1'): ?>
        <div class="alert alert-success">Etudiant ajouté avec succès</div>
    <?php elseif (isset($_GET['success_etudiant']) && $_GET['success_etudiant'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <?php if (isset($_GET['success_modifie']) && $_GET['success_modifie'] == '1'): ?>
        <div class="alert alert-success">Les Informations de l'étudiant ont été modifiés avec succès</div>
    <?php elseif (isset($_GET['success_modifie']) && $_GET['success_modifie'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <?php if (isset($_GET['success_delete']) && $_GET['success_delete'] == '1'): ?>
        <div class="alert alert-success">Les Informations de l'étudiant supprimées avec succès</div>
    <?php elseif (isset($_GET['success_delete']) && $_GET['success_delete'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <div class="intro-prof-list">
        <h1> Liste Des Etudiants</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('ajouter-etudiant') . '?listprof=1&add=1' ?>" class="btn-ajout">Ajouter un
                Etudiant</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
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
                    <th>CNE</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <?php

            foreach ($listeEtudiant as $row) { ?>
                <tr>

                    <td><?= ++$offset; ?></td>
                    <td><?= htmlspecialchars($row->getNom()); ?></td>
                    <td><?= htmlspecialchars($row->getPrenom()); ?></td>
                    <td> <?= htmlspecialchars($row->getCIN()); ?></td>
                    <td> <?= htmlspecialchars($row->getCNE()); ?></td>
                    <td><?= htmlspecialchars($row->getEmail()); ?></td>
                    <td class="btns">
                        <a href="<?= $router->url('modifier-student') . '?listprof=1&modifier=1&cin=' . $row->getCIN(); ?>" class="btn1">Modifier</a>
                        <a id="delete" href="<?= $router->url('supprimer-student') . '?listprof=1&cin=' . $row->getCIN(); ?>" class="btn2">Supprimer</a>
                    </td>
                </tr><?php
            }
            ?>
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
                    classeSelect.innerHTML = '<option value="defaut">Classe</option>';
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