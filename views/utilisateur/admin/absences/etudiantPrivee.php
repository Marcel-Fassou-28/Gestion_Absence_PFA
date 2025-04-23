<?php
namespace APP;
use App\Connection;
use App\Admin\adminTable;
use DateTime;
use DateTimeZone;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);


$line = 1;
$offset = $_GET['p'] * $line;
$n = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['classe'] = $_POST['classe'];
    $_SESSION['matiere'] = $_POST['matiere'];
}

$listeClasse = $list->getAll("classe", "classClasse");
$listeMatiere = $list->getAll("matiere", "classMatiere");

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

if ((isset($_POST['classe']) && $_POST['classe'] !== 'defaut') || (isset($_SESSION['classe']) && $_SESSION['classe'] !== 'defaut')) {
    $classe = $_SESSION['classe'];
    if (isset($_POST['classe']) && !isset($_POST['matiere'])) {

        $_SESSION['matiere'] = 'defaut';
    }
    $listeMatiere = $list->getMatiereByClass($classe);
    $idClasse = $list->getIdClasseByClasseName($classe);
    

}
if ((isset($_POST['matiere']) && $_POST['matiere'] !== 'defaut') || (isset($_SESSION['matiere']) && $_SESSION['matiere'] !== 'defaut')) {
    if (isset($_POST['matiere']) && $_POST['matiere'] !== 'defaut') {
        $_SESSION['matiere'] = $_POST['matiere'];
    }
    $matiere = $_SESSION['matiere'];
    $idMatiere = $list->getIdMatiereByName($matiere);
    $listeEtudiant = $list->getPrivateStudentToPastExamByMatiere($idMatiere,$line,$offset);
    
    $n = count($list->getPrivateStudentToPastExamByMatiere($idMatiere));
}

?>
<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Liste Des Etudiants privees de passer l'examen</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-classe">
                <select name="classe" id="tri">
                    <option value="defaut">Classe</option>
                    <?php
                    foreach ($listeClasse as $row) { ?>
                        <option value="<?= htmlspecialchars($row->getNomClasse()); ?>" <?= (((isset($_POST['classe']) && $_POST['classe'] === $row->getNomClasse()) || (isset($_SESSION['classe']) && $_SESSION['classe'] === $row->getNomClasse())) ? 'selected' : ''); ?>>
                            <?= htmlspecialchars($row->getNomClasse()); ?>
                        </option><?php
                    }
                    ?>
                </select>
            </div>
            <div class="list-classe">
                <select name="matiere" id="tri">
                    <option value="defaut">Matiere</option>
                    <?php
                    foreach ($listeMatiere as $rows) { ?>
                        <option value="<?= htmlspecialchars($rows->getNomMatiere()); ?>" <?= (((isset($_POST['matiere']) && $_POST['matiere'] === $rows->getNomMatiere()) || (isset($_SESSION['matiere']) && $_SESSION['matiere'] === $rows->getNomMatiere())) ? 'selected' : ''); ?>>
                            <?= htmlspecialchars($rows->getNomMatiere()); ?>
                        </option><?php
                    }
                    ?>
                    <!-- Affichage dynamique des matières en fonction de la classe par utilisation du javascript -->
                </select>
            </div>
            <div class="submit-group">
                <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>
        </form>
    </div>
    <div class="list-tri-table-justificatif">
    <?php if (empty($listeEtudiant) || !isset($listeEtudiant)):
                echo " <h1> Aucun etudiants !!!</h1>";
            else:?>
            
        <table>
            <thead>
                <th>N°</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>CNE</th>
            </thead>
            <?php
            
            foreach ($listeEtudiant as $row): ?>
                <tr>
                    <td><?= ++$offset; ?></td>
                    <td> <?=$row->getNom()?></td>
                    <td> <?=$row->getPrenom()?></td>
                    <td> <?=$row->getCNE()?></td>
                </tr><?php

            endforeach;
            endif
            ?>
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
</div>