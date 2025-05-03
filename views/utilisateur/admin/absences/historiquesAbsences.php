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
use App\Connection;
use App\Admin\adminTable;


if (isset($_GET['notifier']) && $_GET['notifier'] == 1): ?>
    <div class="alert alert-success">L'email de notification a été envoyé avec succès</div>
    
    <?php
 $_GET['notifier'] = 5;
elseif (isset($_GET['notifier']) && $_GET['notifier'] == 0): ?>
    <div class="alert alert-danger">Erreur d'envoi de l'email de notification</div>
<?php endif; ?>

<?php

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

/*<a href="<?= $urlUser['modification']; ?>">modifier</a>*/

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$line = 20;
$offset = $_GET['p'] * $line;

$n = count($list->getAll('etudiant', 'classEtudiant'));
$listeEtudiant = $list->getAll('etudiant', 'classEtudiant', $line, $offset);

if (isset($_GET['matiere']) && !isset($_POST['matiere']) && empty($_POST)) {
    $_POST['matiere'] = $_GET['matiere'];
}

if (isset($_GET['classe']) && !isset($_POST['classe'])) {
    $_POST['classe'] = $_GET['classe'];
}





if (isset($_POST['classe'])) {
    if ($_POST['classe'] !== 'defaut') {
        $classe = $_POST['classe'];

        $listeEtudiant = $list->getStudentByClass($classe, $line, $offset);
        $n = count($list->getStudentByClass($classe));
    }
    $_GET['classe'] = $_POST['classe'];

}

if (isset($_POST['matiere'])) {
    if ($_POST['matiere'] !== 'defaut') {
        $matiere = $_POST['matiere'];
        $classe = $list->findClassByMatiere($matiere);

        $idMatiere = $list->getIdMatiereByName($matiere);
        $listeEtudiant = $list->getStudentByClass($classe, $line, $offset);
        $n = count($list->getStudentByClass($classe));
    }
    $_GET['matiere'] = $_POST['matiere'];
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
                    <?php
                    if (isset($classe)): ?>
                        <option value="<?= $classe; ?>" selected><?= $classe; ?></option>
                    <?php endif; ?>
                    <!-- Ensemble des classes tiré de la base de données -- -->
                </select>
            </div>
            <div class="list-classe">
                <select name="matiere" id="tri-matiere">
                    <option value="defaut">Matiere</option>
                    <?php if (isset($matiere)): ?>
                        <option value="<?= $matiere; ?>" selected><?= $matiere; ?></option>
                    <?php endif; ?>
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

            if (isset($idMatiere)): ?>
                <a href="<?= $router->url('notifier') . '?matiere=' . $idMatiere; ?>" style="float: right;" class="btn1"> notifier les
                    Absents</a>
            <?php endif; ?>
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