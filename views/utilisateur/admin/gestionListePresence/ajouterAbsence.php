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
use App\Model\Etudiant;
use App\Model\ListePresence;
use App\Model\Matiere;
use App\Logger;

$pdo = Connection::getPDO();

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$erreur = null;
$success = null;
$filename = $_GET['file'];
$filePath = dirname(__DIR__, 4) .DIRECTORY_SEPARATOR. 'uploads'.DIRECTORY_SEPARATOR.'presence' . DIRECTORY_SEPARATOR . $filename;

$query = $pdo->prepare('SELECT lp.*, CONCAT(p.nom ," ", p.prenom) as nomPrenom FROM listepresence lp JOIN professeur p ON lp.cinProf = p.cinProf WHERE nomFichierPresence =:file');
$query->execute(['file' => $filename]);
$query->setFetchMode(\PDO::FETCH_CLASS, ListePresence::class);
$result = $query->fetch();

if($result) {
    $query1 = $pdo->prepare('SELECT * FROM matiere WHERE nomMatiere = :matiere');
    $query1->execute(['matiere' => $result->getMatiere()]);
    $query1->setFetchMode(\PDO::FETCH_CLASS, Matiere::class);
    $matiere = $query1->fetch();
}


if(isset($_POST['submit-last']) && $_POST['submit-last'] == 'submitAbsence') {
    $nombreAbsence = (int) $_POST['nbrEtudiant'] ?? '';
    $allcinAbsent = $_POST['cinEtudiant'] ?? [];
    $prepareCIN = [];

    $query1 = $pdo->prepare('SELECT e.* FROM etudiant e JOIN classe c ON e.idClasse = c.idClasse WHERE c.nomClasse = :classe');
    $query1->execute(['classe' => $result->getClasse()]);
    $query1->setFetchMode(\PDO::FETCH_CLASS, Etudiant::class);
    $allStudentByClasse = $query1->fetchAll();  

    foreach($allStudentByClasse as $student) {
        for($i=0; $i < $nombreAbsence; $i++) {
            if($allcinAbsent[$i] == $student->getCIN()) {
                $prepareCIN[] = $allcinAbsent[$i];
            }
        }
    }

    if (count($prepareCIN) != $nombreAbsence) {
        $erreur = 1;
    } else {
        $query2 = $pdo->prepare('INSERT INTO absence (date, cinEtudiant, idMatiere) VALUES (?, ?, ?)');
        foreach($prepareCIN as $cin) {
            $query2->execute([$result->getDate(), $cin, $matiere->getIdMatiere()]);
            Logger::log("Consideration des absences de ". $cin , 1, "DB", $_SESSION['id_user'] . ' - ' . $_SESSION['username']);
        }
        $success = 1;
        header('location: ' . $router->url('liste-presence-soumis') .'?listprof=1&p=0&success_absence='.$success);
        exit();
    }

}

?>

<div class="prof-list">
    <?php if ($erreur == 1): ?>
        <div class="alert alert-danger">
            Erreur : Veuillez verifer les/le CIN Saisies, les Étudiants doivent dans la salle susmentionnée
        </div>
    <?php endif ?>
    <div class="intro-prof-list">
        <h1> Details de la liste de Presence</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container-file">
        <section class="professor-info container">
            <div class="prof-group">
                <span><?= htmlspecialchars($result->getNomPrenom()) ?></span>
            </div>
            <div class="classe-group">
                <span><?= htmlspecialchars($result->getClasse()) ?></span>
            </div>
            <div class="classe-group">
                <span><?= htmlspecialchars($result->getMatiere()) ?></span>
            </div>
            <div class="dateprof-group">
                <span><?= htmlspecialchars($result->getDate()) ?></span>
            </div>
        </section>
        <div class="presence-file">
            <div class="btn-show-file">
                <button id="btn-show-file">Afficher la liste d'absence</button>
            </div>
            <div class="presence-file-show">
                <div class="presence-file-show-img">
                    <img src="<?= $router->url('serve-presence') .'?file='.$filename?>" alt="photo-presence">
                </div>
                <form class="edit-creneau-section" method="post" action="">
                    <div>
                        <label for="nbrEtudiant">Nombre d'Etudiants Absents</label>
                        <input type="number" name="nbrEtudiant" value="" min="1" max="40" id="nbrEtudiant" required>
                    </div>
                    <div id="cinContainer">

                    </div>
                    <div>
                        <button value="submitAbsence" class="submit-btn" id="submitBtn" name="submit-last" type="submit">Envoyer</button>
                    </div>
                </form>
        </div>
    </div>
</div>


<script>
    const btnShowFile = document.querySelector('#btn-show-file');
    const fileToShow = document.querySelector('.presence-file-show-img');
    const nbrToAdd = document.querySelector('#nbrEtudiant').value;

    let isShowFile = false;
    btnShowFile.addEventListener('click', () => {
        if (!isShowFile) {
            fileToShow.style.display = 'block';
            isShowFile = true
            btnShowFile.innerText = 'Cacher la liste d\'absence';
        } else {
            fileToShow.style.display = 'none';
            isShowFile = false
            btnShowFile.innerText = 'Afficher la liste d\'absence';
        }
    })

    const nbrEtudiantInput = document.getElementById("nbrEtudiant");
    const cinContainer = document.getElementById("cinContainer");

    nbrEtudiantInput.addEventListener("input", () => {
        const count = parseInt(nbrEtudiantInput.value) || 0;
        cinContainer.innerHTML = ""; // Clear previous fields

        for (let i = 1; i <= count; i++) {
            const div = document.createElement("div");

            const label = document.createElement("label");
            label.textContent = `CIN Étudiant ${i}`;
            label.setAttribute("for", `cin${i}`);

            const input = document.createElement("input");
            input.type = "text";
            input.name = `cinEtudiant[]`; // array-style name
            input.id = `cin${i}`;
            input.required = true;

            div.appendChild(label);
            div.appendChild(input);
            cinContainer.appendChild(div);
        }
    });
</script>