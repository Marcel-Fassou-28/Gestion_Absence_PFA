<?php
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

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeJustificatif = $list->getAllJustificatif();
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

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
                <option value="">Classe</option>
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
                foreach ($listeJustificatif as $row) { ?>
                    <tr><?php
                    foreach ($row as $col => $val) {

                        if (!is_integer($col)) {
                            ?>
                                <td><?= htmlspecialchars($val);
                        }
                    } ?></td>
                        <td><button class="btn1"><a href="">Details</a></button></td>
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
