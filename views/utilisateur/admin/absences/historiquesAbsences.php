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

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

/*<a href="<?= $urlUser['modification']; ?>">modifier</a>*/
use App\Connection;
$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeId = $list->getAbsenceMatiere();
$listeMatiere = $list->getMatiereById($listeId[0]['idMatiere']);

$listeClasse = $list->getClassById($listeMatiere[0]->getIdClasse());

$listeEtudiant = $list->getAbsenceAllStudentByMatiere($listeId[0]['idMatiere'], $listeId[0]['date']);
/*foreach ($listeId as $id):
    $listeMatiere = $list->getMatiereById($id['idMatiere']);

    $listeClasse = $list->getClassById($listeMatiere[0]->getIdClasse());

    $listeEtudiant = $list->getAbsenceAllStudentByMatiere($id['idMatiere'], $id['date']);
    */?>
    <div class="prof-list">
    <div class="intro-prof-list">
        <h1> Liste Des Absences</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
                    <a href="" class="btn-ajout btn">Etudiant Privée de Passer l'Examen</a>
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
            <select name="classe" id="tri-matiere">
                <option value="defaut">Matiere</option>
                <!-- Affichage dynamique des matières en fonction de la classe par utilisation du javascript -->
            </select>
        </div>
        <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>
        </form>
        <!--<div class="absentet">
            <div class="infoA">
            <pre><p>Classe:<?= $listeClasse[0]->getNomClasse(); ?>
            </p></pre>
            <pre><p>Matiere:<?= $listeMatiere[0]->getNomMatiere(); ?>
            </p></pre>
            </div>
            <hr>-->
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
                    $nbreProf = 0;
                    foreach ($listeEtudiant as $row) { ?>
                        <tr>
                            <td><?= ++$nbreProf; ?></td>
                            <td>CIN</td> 
                            <td>CNE</td>
                            <td><?= htmlspecialchars($row->getNom()); ?></td>
                            <td><?= htmlspecialchars($row->getPrenom()); ?></td>
                            <td>1</td>
                        </tr><?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script>
    const apiUrl = "<?= $router->url('api-liste-classe')?>";
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
        matiereSelect.innerHTML = '<option value="">Matiere</option>';

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
