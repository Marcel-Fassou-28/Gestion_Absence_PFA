<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

use App\Professeur\ProfessorTable;
use App\Connection;

$line = 20;
$offset = $_GET['p'] * $line;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$cinProf = $_SESSION['id_user'];

$tableMatiere = $professeurTable->getMatiere($cinProf);
$tableClasse = $professeurTable->getClasse($cinProf);
$listeEtudiant = $professeurTable->getAbsents($cinProf, $line, $offset);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-first'])) {
    $class = $_POST['classe-prof'] ?? '';
    $matiere = $_POST['matiere-prof'] ?? '';

    if (!empty($class)) {
        $idClasse = array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class)[array_key_first(array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class))]->getIDClasse();
        $listeEtudiant = $professeurTable->getAbsentsByClasse($cinProf, $idClasse, $line, $offset);
    }
    elseif (!empty($class) && !empty($matiere)) {
        $idClasse = array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class)[array_key_first(array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class))]->getIDClasse();
        $idMatiere = array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere)[array_key_first(array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere))]->getIdMatiere();
        $listeEtudiant = $professeurTable->getAbsentsByMatiereClasse($cinProf, $idClasse , $idMatiere, $line, $offset);
    } else {
        $listeEtudiant = $professeurTable->getAbsents($cinProf, $line, $offset);
    }
}

$n = count($listeEtudiant);
?>

<div class="absence">
    <div class="intro">
        <h1>Historique des absences</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="absence-container">
        <form class="professor-info container" method="post" action="">
            <div class="level-group">
                <select id="tri-classe" name="classe-prof">
                    <option value="">Sélectionner une classe</option>

                </select>
            </div>
            <div class="subject-group">
                <select id="tri-matiere" name="matiere-prof">
                    <option value="">Sélectionner une matière</option>

                </select>
            </div>
            <div class="submit-group">
                <input class="submit-btn" type="submit" name="submit-first" value="Afficher les Étudiants">
            </div>
        </form>
        <?php if (!empty($listeEtudiant)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Nom et Prénom</th>
                            <th>Nombre d'absences</th>
                            <th>Dates et Créneaux</th>
                            <th>Classe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero = 1; ?>
                        <?php foreach ($listeEtudiant as $cin => $etudiant): ?>
                            
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($cin) ?></td>
                                <td><?= htmlspecialchars($etudiant->getCne()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNombreAbsences()) ?></td>
                                <td>
                                    <ul class="absences-list">
                                        <?php foreach ($etudiant->getAbsences() as $absence): ?>
                                            <li><?= htmlspecialchars(formatAbsence($absence)) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td><?= htmlspecialchars($etudiant->getNomClasse()) ?></td>
                            </tr>
                            
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-data">Aucune absence enregistrée.</p>
        <?php endif; ?>
        <div class="submit-group-historic">
            <?php
                $nbrpage = ceil($n / $line);
                for ($i = 0; $i < $nbrpage; ) { ?>
                    <a href="?<?= $professeurTable->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a><?php
                }
            ?>
            </div>
    </div>
</div>

<?php
// Fonction pour formater les absences
function formatAbsence($absence) {
    // Exemple : "2025-04-12 00:00:00-23:20:00" -> "12/04/2025, 00:00-23:20"
    list($date, $time) = explode(' ', $absence);
    $date = date('d/m/Y', strtotime($date));
    return "$date, $time";
}
?>


<script>
    const apiUrl = "<?= $router->url('api-prof-liste-clm') . '?cinProf='.$cinProf?>";
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
