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
//variable n pour compter le nombre total de ligne extraite de la fonction getALL()
$n = count($list->getAll('filiere', 'classFiliere'));


$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');



// utilisation de la variable de get pour gerer la pagination lors du tri 
if (isset($_GET['filiere']) && !isset($_POST['filiere']) && empty($_POST)) {
    $_POST['filiere'] = $_GET['filiere'];
}
if (isset($_GET['classe']) && !isset($_POST['classe']) && empty($_POST)) {
    $_POST['classe'] = $_GET['classe'];
}
//extraction de tous les departement 
$listeDepartement = $list->getAll('departement','classDepartement');
 // extraction de toutes les filiere
 $listeFiliere = $list->getAll('filiere','classFiliere',$line,$offset);
// tri si le departement  est choisit
if (isset($_POST['departement'])) {
    if ($_POST['departement'] !== 'defaut') {
        $departement = $_POST['departement'];
        
        $n = count($list->fieldsByDepartement($departement));
        $listeFiliere = $list->fieldsByDepartement($departement,$line,$offset);
    }
    $_GET['departement'] = $_POST['departement'];
}



?>
<div class="prof-list">
    <?php if (isset($_GET['success_filiere']) && $_GET['success_filiere'] == '1'): ?>
        <div class="alert alert-success">Filiere ajouté avec succès</div>
    <?php elseif (isset($_GET['success_filiere']) && $_GET['success_filiere'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <?php if (isset($_GET['success_modifie']) && $_GET['success_modifie'] == '1'): ?>
        <div class="alert alert-success">Les Informations de la filiere ont été modifiés avec succès</div>
    <?php elseif (isset($_GET['success_modifie']) && $_GET['success_modifie'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == '1'): ?>
        <div class="alert alert-success">Les Informations de la filiere supprimées avec succès</div>
    <?php elseif (isset($_GET['delete_success']) && $_GET['delete_success'] == '0'): ?>
        <div class="alert alert-danger">Cette opération n'a pas pu être Effectué</div>
    <?php else: ?><?php endif ?>

    <div class="intro-prof-list">
        <h1> Liste De toutes les  filiere </h1><br>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('liste-filiere-ajouter') . '?listprof=1' ?>" class="btn-ajout"> Ajouter une filiere</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
        <div class="list-departement">
                <select name="departement" id="tri-departement" onchange="this.form.submit()">
                    <option value="defaut">Département</option>
                    <?php 
                    foreach($listeDepartement as $departement):?>
                    <option value="<?=$departement->getNomDepartement();?>" <?= ((isset($_POST['departement']) && $_POST['departement'] === $departement->getNomDepartement()) || (isset($_GET['departement']) && $_GET['departement'] === $departement->getNomDepartement()))? 'selected': ''?> ><?=$departement->getNomDepartement();?></option>
                    <?php endforeach?>
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
                    <th>Nom Filiere</th>
                    <th>Alias</th>
                    
                    <th>Actions</th>
                </tr>
            </thead>
            <?php
            
            foreach ($listeFiliere as $row) { ?>
                <tr>
                    

                    <td><?= ++$offset; ?></td>
                    <td><?= htmlspecialchars($row->getNomFiliere()); ?></td>
                    <td><?= htmlspecialchars($row->getAlias()); ?></td>
                    
                    <td class="btns">
                        <a href="<?= $router->url('liste-filiere-modifie') . '?listprof=1&modifier=1&id=' . $row->getIDFiliere(); ?>" class="btn1">Modifier</a>
                        <a id="delete" href="<?= $router->url('liste-filiere-delete') . '?listprof=1&id=' . $row->getIDFiliere(); ?>" class="btn2">Supprimer</a>
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