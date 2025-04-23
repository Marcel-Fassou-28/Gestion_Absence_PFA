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

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

if ((isset($_POST['classe']) && $_POST['classe'] !== 'defaut')) {
    $classe = $_POST['classe'];
   
    $listeMatiere = $list->getMatiereByClass($classe);
    $idClasse = $list->getIdClasseByClasseName($classe);
    

}
if ((isset($_POST['matiere']) && $_POST['matiere'] !== 'defaut')) {
    
    $matiere = $_POST['matiere'];
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
                <select name="classe" id="tri-classe">
                    <option value="defaut">Classe</option>
                    
                </select>
            </div>
            <div class="list-classe">
                <select name="matiere" id="tri-matiere">
                    <option value="defaut">Matiere</option>
                    
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
            <h3>Matiere: <?= $matiere?></h3>
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

<script>
        const apiUrl = "<?= $router->url('api-liste-classe') ?>";
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
                    matiereSelect.innerHTML = '<option value="defaut" selected >matiere</option>';

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