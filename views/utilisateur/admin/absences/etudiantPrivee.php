<?php
namespace APP;

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}


use App\Connection;
use App\Admin\adminTable;
use DateTime;
use DateTimeZone;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);


$line = 20;
$offset = $_GET['p'] * $line;
$n = 0;

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$listeEtudiantComplet = $list->getPrivateStudentToPastExam($line, $offset);
$n = count($listeEtudiantComplet);

if (isset($_GET['notifier']) && $_GET['notifier'] == 1): ?>
    <div class="alert alert-success">L'email de notification a été envoyé avec succès</div>
    
    <?php
 $_GET['notifier'] = 5;
elseif (isset($_GET['notifier']) && $_GET['notifier'] == 0): ?>
    <div class="alert alert-danger">Erreur d'envoi de l'email de notification</div>
<?php endif;

if ((isset($_POST['classe']) && $_POST['classe'] !== 'defaut')) {
    $classe = $_POST['classe'];

    $listeMatiere = $list->getMatiereByClass($classe);
    $idClasse = $list->getIdClasseByClasseName($classe);
}

if ((isset($_POST['matiere']) && $_POST['matiere'] !== 'defaut')) {

    $matiere = $_POST['matiere'];
    $idMatiere = $list->getIdMatiereByName($matiere);
    $listeEtudiant = $list->getPrivateStudentToPastExamByMatiere($idMatiere, $line, $offset);

    $n = count($list->getPrivateStudentToPastExamByMatiere($idMatiere));
}

?>

<div class="prof-list">
    <div class="intro-prof-list">
        <h1> Liste Des Etudiants prives de passer l'examen</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-classe">
                <select name="classe" id="tri-classe" required>
                    <option value="defaut">Classe</option>
                    <?php if (isset($classe)): ?>
                        <option value="<?= $classe; ?>" selected><?= $classe; ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="list-classe">
                <select name="matiere" id="tri-matiere" required>
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
    </div>
    <div class="list-tri-table-justificatif">
        <?php if(isset($listeEtudiant) && !empty($listeEtudiant)): ?>
            <?php if (empty($listeEtudiant) || !isset($listeEtudiant)): ?>
                <p class = "liste-vide"> Aucun etudiants !!!</p>
            <?php else: ?>
                <a href="<?= $router->url('exportPdf') . '?matiere=' . $matiere ?>" class="btn-download-pdf submit-btn" target="_blank">
                    Télécharger en PDF
                </a>
                <table>
                    <thead>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>CNE</th>
                    </thead>
                    <?php foreach ($listeEtudiant as $row): ?>
                        <tr>
                            <td><?= ++$offset; ?></td>
                            <td><?= htmlspecialchars($row->getNom()) ?></td>
                            <td><?= htmlspecialchars($row->getPrenom()) ?></td>
                            <td><?= htmlspecialchars($row->getCNE()) ?></td>
                        </tr>
                    <?php endforeach ?>
            <?php endif ?>
        <?php else: ?>
            <?php if(!empty($listeEtudiantComplet)): ?>
                <table>
                <thead>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>CNE</th>
                    <th>Matiere</th>
                    <th>Classe</th>
                </thead>
                <?php foreach ($listeEtudiantComplet as $row): ?>
                    <tr>
                        <td><?= ++$offset; ?></td>
                        <td><?= htmlspecialchars($row->getNom()) ?></td>
                        <td><?= htmlspecialchars($row->getPrenom()) ?></td>
                        <td><?= htmlspecialchars($row->getCNE()) ?></td>
                        <td><?= htmlspecialchars($row->getNomMatiere()) ?></td>
                        <td><?= htmlspecialchars($row->getNomClasse()) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <p class = "liste-vide"> Aucun etudiants !!!</p>
            <?php endif ?>
        <?php endif ?>
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

    if (isset($idMatiere)): ?>
        <a href="<?= $router->url('notifier') . '?matiere=' . $idMatiere . '&privee=1'; ?>" style="float: right;" class="btn1"> notifier ces etudiants</a>
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