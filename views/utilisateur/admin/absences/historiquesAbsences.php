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





$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

/*<a href="<?= $urlUser['modification']; ?>">modifier</a>*/
use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$line = 20;
$offset = $_GET['p'] * $line;

$n = count($list->getAll('etudiant', 'classEtudiant'));
$listeEtudiant = $list->getAll('etudiant', 'classEtudiant', $line, $offset);


if ((isset($_POST['classe']) && $_POST['classe'] !== 'defaut')) {
    $classe = $_POST['classe'];
    
    $listeEtudiant = $list->getStudentByClass($classe, $line, $offset);
    $n = count($list->getStudentByClass($classe));


}
if ((isset($_POST['matiere']) && $_POST['matiere'] !== 'defaut')) {


    $matiere = $_POST['matiere'];
    $classe = $list->findClassByMatiere($matiere);
    $_POST['classe'] = $classe;
    $idMatiere = $list->getIdMatiereByName($matiere);
    $listeEtudiant = $list->getStudentByClass($classe, $line, $offset);
    $n = count($list->getStudentByClass($classe));
}


?>
<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Liste Des Absences</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="<?= $router->url('etudiantprivee') . '?listprof=1' . '&justifier=1' . '&p=0'; ?>"
                class="btn-ajout btn">Etudiant Privée de Passer l'Examen</a>
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
                <select name="matiere" id="tri-matiere">
                    <option value="defaut">Matiere</option>
                    <!-- Affichage dynamique des matières en fonction de la classe par utilisation du javascript -->
                </select>
            </div>
            <div class="submit-group">
                <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>
        </form>
        <div>
            <div class="list-tri-table">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Nombre d'Absences</th>
                        </tr>
                    </thead>
                    <?php

                    foreach ($listeEtudiant as $row) { ?>
                        <tr>
                            <td><?= ++$offset; ?></td>
                            <td><?= htmlspecialchars($row->getCIN()); ?></td>
                            <td><?= htmlspecialchars($row->getCNE()); ?></td>
                            <td><?= htmlspecialchars($row->getNom()); ?></td>
                            <td><?= htmlspecialchars($row->getPrenom()); ?></td>

                            <td><?= (isset($idMatiere) ? $list->getAbsenceStudentByMatiere($row->getCIN(), $idMatiere) : 'N/A') ?>
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

                <a href="?<?= $list->test('p', $i); ?>"
                    class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a><?php
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