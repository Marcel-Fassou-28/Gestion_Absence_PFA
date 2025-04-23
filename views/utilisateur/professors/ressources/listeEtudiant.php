<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

use App\Connection;
use App\Professeur\ProfessorTable;

$pdo = Connection::getPDO();
$professeurTable = new ProfessorTable($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$moisEnFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
    'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
    'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');
$dateSql = $date->format('Y-m-d H:i:s');

$cinProf = $_SESSION['id_user'];
$listeEtudiant = $professeurTable->findStudent($cinProf);
$listDesAbsents = $professeurTable->getAllStudentAbsenceState($cinProf);
$listeComplete = $professeurTable->getAllStudentList($listeEtudiant, $listDesAbsents);

$filiere = ''; 
$matiere = '';
$class = '';

$tableFiliere = $professeurTable->getFiliere($cinProf);
$tableClasse = $professeurTable->getClasse($cinProf);
$tableMatiere = $professeurTable->getMatiere($cinProf);
$listeEtudiant = $professeurTable->findStudent($cinProf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $filiere = $_POST['filiere-prof'] ?? '';
    $matiere = $_POST['matiere-prof'] ?? '';
    $class = $_POST['classe-prof'] ?? '';

    $idMatiere = (int) array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere)[array_key_first(array_filter($tableMatiere, fn($m) => $m->getNomMatiere() === $matiere))]->getIdMatiere();
    $idClasse = (int) array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class)[array_key_first(array_filter($tableClasse, fn($c) => $c->getNomClasse() === $class))]->getIDClasse();

    if (!empty($class) && !empty($matiere)) {

        $listeEtudiants = $professeurTable->findStudentByClass($idClasse);
        $listAbsents = $professeurTable->getNbrAbsence($cinProf, $idClasse, $idMatiere);
        $listeComplete = $professeurTable->getAllStudentList($listeEtudiants, $listAbsents);
    }
}
?> 

<div class="presence">
    <div class="intro">
        <h1>Liste des Etudiants</h1>
        <div class="date-group-etudiant">
            <input type="text" value="<?= $dateDuJour ?>" readonly>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container">
        <form class="professor-info container" method="post" action="">
            <div class="filiere-group">
                <select id="filiere" name="filiere-prof" required>
                    <option value="">Filière</option>
                    
                </select>
            </div>
            <div class="level-group">
                <select id="classe" name="classe-prof" required>
                    <option name="classe-prof" value="" >Classe</option>
                    
                </select>
            </div>
            <div class="subject-group">
                <select id="matiere" name="matiere-prof" required>
                    <option name="matiere-prof"  value="">Matière</option>
                    
                </select>
            </div>
            <div>
                <input class="submit-btn" type="submit" name="submit" value="Afficher les Etudiants">
            </div>
        </form>
        <?php if (!empty($listeEtudiant)): ?>
            <section class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Nom et Prénom</th>
                            <th>Nombre d'Absence</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $numero = 1; ?>
                        <?php foreach ($listeComplete as $etat): ?>
                            <tr>
                                <td><?= sprintf("%02d", $numero++) ?></td>
                                <td><?= htmlspecialchars($etat->getCINEtudiant()) ?></td>
                                <td><?= htmlspecialchars($etat->getCNE()) ?></td>
                                <td><?= htmlspecialchars($etat->getNom() . ' ' . $etat->getPrenom()) ?></td>
                                <td><?= htmlspecialchars($etat->getNbrAbsence()) ?></td>
                                <td><button class="show-state" data-modal-id="modal-<?= $numero ?>">Voir plus</button></td>
                            </tr>
                            <div class="modal" id="modal-<?= $numero ?>">
                                <div class="modal-overlay"></div>
                                <div class="modal-content">
                                    <div>
                                        <p>CIN: </p>
                                        <p><?= htmlspecialchars($etat->getCINEtudiant()) ?></p>
                                    </div>
                                    <div>
                                        <p>CNE: </p>
                                        <p><?= htmlspecialchars($etat->getCNE()) ?></p>
                                    </div>
                                    <div>
                                        <p>Nom: </p>
                                        <p><?= htmlspecialchars($etat->getNom()) ?></p>
                                    </div>
                                    <div>
                                        <p>Prénom: </p>
                                        <p><?= htmlspecialchars($etat->getPrenom()) ?></p>
                                    </div>
                                    <div>
                                        <p>Nombre d'Absence: </p>
                                        <p><?= htmlspecialchars($etat->getNbrAbsence()) ?></p>
                                    </div>
                                    <div class="modal-buttons">
                                        <button class="btn-modal close-modal">Fermer</button>
                                        <button class="btn-modal">
                                            <a href="mailto:<?= $etat->getEmail() ?>">Contactez</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    <?php endif ?>
</div>


<script>
    const apiUrl = "<?= $router->url('api-prof-liste-etud') . '?cinProf='.$cinProf?>";
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const filiereSelect = document.querySelector('#filiere');
            const classeSelect = document.querySelector('#classe');
            const matiereSelect = document.querySelector('#matiere');

            const filiereData = {};

            // Remplir le select de filières
            data.forEach(filiere => {
            const option = document.createElement('option');
            option.value = filiere.nomFiliere;
            option.textContent = filiere.nomFiliere;
            filiereSelect.appendChild(option);

            filiereData[filiere.nomFiliere] = filiere.classes;
            });

            // Lorsqu’on change de filière
            filiereSelect.addEventListener('change', function () {
            const selectedFiliere = this.value;
            classeSelect.innerHTML = '<option value="">Classe</option>';
            matiereSelect.innerHTML = '<option value="">Matière</option>';
            matiereSelect.disabled = true;

            if (selectedFiliere && filiereData[selectedFiliere]) {
                classeSelect.disabled = false;
                console.log('dfsf');

                filiereData[selectedFiliere].forEach(classe => {
                const option = document.createElement('option');
                option.value = classe.nomClasse;
                option.textContent = classe.nomClasse;
                classeSelect.appendChild(option);
                });
            } else {
                classeSelect.disabled = true;
            }
            });

            // Lorsqu’on change de classe
            classeSelect.addEventListener('change', function () {
            const selectedFiliere = filiereSelect.value;
            const selectedClasse = this.value;
            matiereSelect.innerHTML = '<option value="">Matière</option>';

            const classeList = filiereData[selectedFiliere] || [];
            const selectedClasseObj = classeList.find(cl => cl.nomClasse === selectedClasse);

            if (selectedClasseObj && selectedClasseObj.matieres.length > 0) {
                matiereSelect.disabled = false;

                selectedClasseObj.matieres.forEach(matiere => {
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
        .catch(error => console.error('Erreur de chargement :', error));
</script>
