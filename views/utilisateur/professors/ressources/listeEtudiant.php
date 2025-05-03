<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if(isset($_SESSION['id_user']) && $_SESSION['role'] != 'professeur') {
    header('location: ' . $router->url('user-dashboard' , ['role' => $_SESSION['role']]));
    exit();
}
$title = "Professeur";
use App\Connection;
use App\Professeur\ProfessorTable;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

$line = 20;
$offset = $_GET['p'] * $line;

$cinProf = $_SESSION['id_user'];
$listeEtudiants = $professeurTable->findStudent($cinProf, $line, $offset);

$class = '';
$tableClasse = $professeurTable->getClasse($cinProf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $class = trim($_POST['classe-prof']) ?? '';

    $classeSelectionnee = current(array_filter($tableClasse, fn($c) => $c->getIDClasse() == $class));
    $idClasse = $classeSelectionnee ? (int) $classeSelectionnee->getIDClasse() : null;

    if (!empty($class) && isset($idClasse)) {

        $listeEtudiants = $professeurTable->findStudentByClass($idClasse, $line, $offset);
    } else {
        $listeEtudiants = $professeurTable->findStudent($cinProf, $line, $offset);
    }
    
}

$n = count($listeEtudiants);

?> 

<div class="presence">
    <div class="intro">
        <h1>Liste des Etudiants</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container">
        <form class="professor-info container" method="post" action="">
            <div class="level-group">
                <select id="classe" name="classe-prof" required>
                    <option name="classe-prof" value="" >Classe</option>
                    
                </select>
            </div>
            <div>
                <input class="submit-btn" type="submit" name="submit" value="Afficher les Etudiants">
            </div>
        </form>
            <section class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Classe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero = 1; ?>
                    <?php if (!empty($listeEtudiants)): ?>
                        <?php foreach ($listeEtudiants as $etudiant): ?>
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($etudiant->getCIN()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getCNE()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNom()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getPrenom()) ?></td>
                                <td><?= htmlspecialchars($etudiant->getNomClasse()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif ?>
                    </tbody>
                </table>
            </section>
            <div class="submit-group">
            <?php
                $nbrpage = ceil($n / $line);
                for ($i = 0; $i < $nbrpage; ) { ?>
                    <a href="?<?= $professeurTable->test('p', $i); ?>" class="btn1 <?= ($_GET['p'] == $i) ? 'page' : ''; ?>"><?= ++$i ?></a><?php
                }
            ?>
            </div>
        </div>
</div>

<script>
    const apiUrl = "<?= $router->url('api-prof-liste-etud') . '?cinProf=' . $cinProf ?>";

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            const classeSelect = document.querySelector('#classe');
            const matiereSelect = document.querySelector('#matiere'); // Assurez-vous que l'élément #matiere existe dans le HTML

            // Remplir le select des classes
            data.forEach(classe => {
                const option = document.createElement('option');
                option.value = classe.idClasse;
                option.textContent = classe.nomClasse;
                classeSelect.appendChild(option);
            });

            // Lorsque la classe change, vider le select des matières
            classeSelect.addEventListener('change', function () {
                const selectedClasseId = parseInt(this.value);
                matiereSelect.innerHTML = '<option value="">Matière</option>';
                matiereSelect.disabled = true;

                // Ici, il n'y a pas de données sur les matières, donc ce bloc peut être personnalisé plus tard
                // Une fois que les matières sont ajoutées à l'API, décommenter et utiliser ce code.
                /*
                const selectedClasse = data.find(classe => classe.idClasse === selectedClasseId);
                if (selectedClasse && selectedClasse.matieres.length > 0) {
                    selectedClasse.matieres.forEach(matiere => {
                        const option = document.createElement('option');
                        option.value = matiere.idMatiere;
                        option.textContent = matiere.nomMatiere;
                        matiereSelect.appendChild(option);
                    });
                    matiereSelect.disabled = false;
                }
                */
            });
        })
        .catch(error => console.error('Erreur de chargement :', error));
</script>
