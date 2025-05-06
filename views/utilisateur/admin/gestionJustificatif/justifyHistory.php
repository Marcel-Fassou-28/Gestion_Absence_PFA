<?php
if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}

use App\Connection;
use App\Admin\adminTable;



$title = "Administration";
$line = 20;
$offset = $_GET['p'] * $line;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeJustificatif = $list->getAllJustificatif($line, $offset);
$n = count($list->getAllJustificatif());
$listeClasse = $list->getAll("classe", "classClasse");
$listeMatiere = $list->getAll("matiere", "classMatiere");

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

if ((isset($_POST['classe']) && $_POST['classe'] !== 'defaut')) {
    $classe = $_POST['classe'];


    $listeMatiere = $list->getMatiereByClass($classe);
    $idClasse = $list->getIdClasseByClasseName($classe);
    $listeJustificatif = $list->getAllJustificatif($line, $offset, 0, $idClasse);
    $n = count($list->getAllJustificatif(idClasse: $idClasse));

}

if ((isset($_POST['Matiere']) && $_POST['Matiere'] !== 'defaut')) {


    $matiere = $_POST['Matiere'];
    $idMatiere = $list->getIdMatiereByName($matiere);
    $listeJustificatif = $list->getAllJustificatif(0, 0, $idMatiere);
    $n = count($list->getAllJustificatif(0, 0, $idMatiere));
}
?>



<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Historique des Justificatifs</h1>
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
                    <!-- Ensemble des classes tiré de la base de données -- -->
                </select>
            </div>
            <div class="list-classe">
                <select name="Matiere" id="tri-matiere">
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

        <table>
            <thead>
                <th>N°</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>CNE</th>
                <th>Date de soumission</th>
                <th>Action</th>
            </thead>
            <?php

            foreach ($listeJustificatif as $row): ?>
                <tr>
                    <td><?= ++$offset; ?></td>
                    <?php

                    foreach ($row as $col => $val):
                        if ($col !== 'id' && $col !== 'statut'):
                            ?>

                            <td><?= htmlspecialchars($val); ?></td>

                            <?php
                        elseif ($col === 'id'):
                            $value = $val;
                        else:
                            $statut = $val;
                        endif;
                    endforeach;
                    if (isset($value) && $statut === 'en attente'): ?>

                        <td><a href="<?= $router->url('detail_justificatif') . '?listprof=1' . '&idjustificatif=' . $value; ?>"
                                class="btn1">Details</a></td>
                    <?php elseif (isset($statut) && $statut !== 'en attente'): ?>
                        <td><a href="<?= $router->url('detail_justificatif') . '?listprof=1' . '&idjustificatif=' . $value; ?>"
                                class="btn1">Deja Traiter</a></td>
                    <?php endif; ?>
                </tr><?php

            endforeach
            ?>
        </table>
    </div>
</div>
<?php
// variable pour compter le nombre de page 
//pour aficher le nombre total de page avec ou sans tri 
$nbrpage = ceil($n / $line);
//boucle d'affichage des numero de page 
for ($i = 0; $i < $nbrpage; ) { ?>

    <a href="?<?= $list->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a>
    <?php
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
                matiereSelect.innerHTML = '<option value="defaut">Matiere</option>';

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