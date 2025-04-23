<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Admin\adminTable;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*<a href="<?= $urlUser['modification']; ?>">modifier</a>*/
use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

//variable pour la gestion de la pagination
$line = 20;
$offset = $_GET['p'] * $line;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

//variable n pour compter le nombre total de ligne extraite de la fonction getALL()
$n = count($list->getAll("departement", "classDepartement"));
$listeDepart = $list->getAll("departement", "classDepartement");
$listeFiliere = $list->getAll("filiere", "classFiliere");
$listeClasse = $list->getAll("classe", "classClasse");
$listeProf = $list->getAll("professeur", "classProf");

// utilisation de la variable de session pour gerer la pagination lors du tri 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['filiere'] = $_POST['filiere'];
    $_SESSION['classe'] = $_POST['classe'];
    $_SESSION['departement'] = $_POST['departement'];
}

//affichage des etudiants sans tri
if ( (empty( $_POST)) || ($_SESSION['departement'] === 'defaut' && $_SESSION['filiere'] === 'defaut' && $_SESSION['classe'] === 'defaut'  )) {
    $listeProf = $list->getAll("professeur", "classProf" , $line, $offset);
}

// tri si le departement  est choisit
if ((isset($_POST['departement']) && $_POST['departement'] !== 'defaut')  || (isset($_SESSION['departement']) && $_SESSION['departement'] !== 'defaut')) {
    $departement = $_SESSION['departement'];
    if (isset($_POST['departement']) && !isset($_POST['filiere']) && !isset($_POST['classe'])){
        $_SESSION['filiere'] = 'defaut';
        $_SESSION['classe'] = 'defaut';
    }
    $n = count($list->getprofByDepartement("departement"));
    $listeFiliere = $list->fieldsByDepartement($departement);
    $listeProf = $list->getprofByDepartement($departement,$line,$offset);
}

// tri si la filiere est choisit
if ((isset($_POST['filiere']) && $_POST['filiere'] !== 'defaut') || (isset($_SESSION['filiere']) && $_SESSION['filiere'] !== 'defaut')) {
    $filiere = $_SESSION['filiere'];
    if (isset($_POST['filiere']) && !isset($_POST['classe'])){
        
        $_SESSION['classe'] = 'defaut';
    }
    $n =count( $list->getProfByFiliere($filiere));
    $listeClasse = $list->classByFields($filiere);
    $listeProf = $list->getProfByFiliere($filiere,$line,$offset);
}

if ((isset($_POST['classe']) && $_POST['classe'] !== 'defaut') || (isset($_SESSION['classe']) && $_SESSION['classe']!== 'defaut')) {
    if (isset($_POST['classe']) && $_POST['classe'] !== 'defaut')
    {
        $_SESSION['classe'] = $_POST['classe'];
    }
    $classe = $_SESSION['classe'];
    $n = count($list->getProfByClass($classe));
    $listeProf = $list->getProfByClass($classe,$line,$offset);
} 
?>
<div class="prof-list">
<?php if (isset($_GET['success_prof']) && $_GET['success_prof'] == '1'): ?>
        <div class="alert alert-success">Professeur ajouté avec succès</div>
    <?php elseif(isset($_GET['success_prof']) && $_GET['success_prof'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

        <?php if (isset($_GET['modifie_success']) && $_GET['modifie_success'] == '1'): ?>
        <div class="alert alert-success">Informations du professeur modifié avec succès</div>
    <?php elseif(isset($_GET['modifie_success']) && $_GET['modifie_success'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

        <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == '1'): ?>
        <div class="alert alert-success">Informations du professeur supprimé avec succès</div>
    <?php elseif(isset($_GET['delete_success']) && $_GET['delete_success'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <div class="intro-prof-list">
        <h1> Liste Des Professeurs</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('ajouterProf').'?listprof=1&p=0&modifier=1';?>" class="btn-ajout">Ajouter un Professeur</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-departement">
            <select name="departement" id="tri-departement" onchange="this.form.submit()">
                <option value="">Département</option>
                <?php
                /*foreach ($listeDepart as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomDepartement()); ?>" <?= (((isset($_POST['departement']) && $_POST['departement'] === $row->getNomDepartement()) || (isset($_SESSION['departement']) && $_SESSION['departement'] === $row->getNomDepartement())) ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomDepartement()); ?>
                    </option><?php
                }*/
                ?>
            </select>
            </div>
            <div class="list-filiere">
            <select name="filiere" id="tri-filiere" onchange="this.form.submit()">
                <option value="">Filières</option>
                <?php
                /*foreach ($listeFiliere as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomFiliere()); ?>" <?= (((isset($_POST['filiere']) && $_POST['filiere'] === $row->getNomFiliere()) || (isset($_SESSION['filiere']) && $_SESSION['filiere'] === $row->getNomFiliere() )) ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomFiliere()); ?>
                    </option><?php
                }*/
                ?>
            </select>
            </div>
            <div class="list-classe">
            <select name="classe" id="tri-classe">
                <option value="">Classe</option>
                <?php
                /*foreach ($listeClasse as $row) { ?>
                    <option value="<?= htmlspecialchars($row->getNomClasse()); ?>" <?= (((isset($_POST['classe']) && $_POST['classe'] === $row->getNomClasse())|| (isset($_SESSION['classe'])&& $_SESSION['classe'] === $row->getNomClasse())) ? 'selected' : ''); ?>>
                        <?= htmlspecialchars($row->getNomClasse()); ?>
                    </option><?php
                }*/
                ?>
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
    for ( $i = 0; $i <$nbrpage; ){?>

        <a href="?<?= $list->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page': '';?>"><?=++$i?></a><?php
    }
    ?>
</div>
<script>
    const apiUrl = "<?= $router->url('api-liste-departement')?>";
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
            filiereSelect.innerHTML = '<option value="">Filières</option>';
            classeSelect.innerHTML = '<option value="">Classes</option>';
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
            classeSelect.innerHTML = '<option value="">Classe</option>';
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
