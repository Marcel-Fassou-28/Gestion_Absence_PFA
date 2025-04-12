<?php 

use App\Connection;
use App\UserTable;
use App\ProfessorTable;
use App\EtudiantTable;

function encodindCIN($cin) {
    global $secretKey;
    return  hash_hmac('sha256', $cin, $secretKey);
}

if (isset($_SESSION)) {
    $pdo = Connection::getPDO();
    $table = new UserTable($pdo);
    $academic = new ProfessorTable($pdo);
    $student = new EtudiantTable($pdo);
    $studentFiliere = $student->getFiliere($_SESSION['id_user']);

    $user = $table->getIdentification($_SESSION['id_user']);
    $academic_info = [
        'matiere' => $academic->getMatiere($_SESSION['id_user']),
        'filiere' => $academic->getFiliere($_SESSION['id_user'])
    ];

}

?>


<div class="container profil-interface">
    <div class="image-section">
        <img src="/images/profil.jpg" alt="photo de profil">
        <h3><?= htmlspecialchars($user->getRole() . ' ' . $user->getNom())?></h3>
    </div>
    <div class="container-useful">
    <div class="info-section">
        <h3>Vos Informations</h3>
        <a href="">Edit profile</a>
        <div class="personal-info">
            <div>
                <p>Email: </p>
                <p><?= htmlspecialchars($user->getEmail()) ?></p>
            </div>
            <div>
                <p>Username: </p>
                <p><?= htmlspecialchars($user->getUsername()) ?></p>
            </div>
            <div>
                <p>CIN: </p>
                <p><?= htmlspecialchars($user->getCIN()) ?></p>
            </div>
            <div>
                <p>Nom: </p>
                <p><?= htmlspecialchars($user->getNom()) ?></p>
            </div>
            <div>
                <p>Prénom:</p>
                <p><?= htmlspecialchars($user->getPrenom()) ?></p>
            </div>
            <?php if ($_SESSION['role'] === 'professeur'): ?>
            <div>
                <p>Matières enseignées: </p>
                <p><?php foreach ($academic_info['matiere'] as $matiere) {
                    echo $matiere->getNomMatiere() . ', ';
                }?></p>
            </div>
            <?php endif ?>
            <?php if($_SESSION['role'] === 'etudiant'): ?>
                <div>
                <p>Filière: </p>
                <p><?= htmlspecialchars($studentFiliere->getNomFiliere()) ?></p>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="academic-section">
        <div class="departement">
        <?php if ($_SESSION['role'] === 'professeur') : ?>
            <p>Departement: </p>
            <p><?= htmlspecialchars($academic->getDepartementProf($_SESSION['id_user'])->getNomDepartement()) ?></p>
        <?php elseif($_SESSION['role'] === 'etudiant'): ?>
            <p>Departement: </p>
            <p><?= htmlspecialchars($student->getDepartementEtudiant($_SESSION['id_user'])->getNomDepartement()) ?></p>
        <?php endif ?> 
        </div>
        <?php if ($_SESSION['role'] === 'professeur') : ?>
            <div class="filiere">
                <p>Filière d'Affectation: </p>
                <p><?php foreach ($academic_info['filiere'] as $filiere) {
                    echo $filiere->getNomFiliere() . ', ';
                }?></p>
            </div>
        <?php endif ?>
    </div>
    <div class="useful-link-section">
        <h3>Useful Link</h3>
        <ul>
            <li><a href="">Calendrier</a></li>
            <li><a href="">Listes des élèves</a></li>
            <li><a href="">Liste des étudiants</a></li>
        </ul>
    </div>
    <div class="historic-section">
        <h3>Historiques</h3>
        <ul>
            <li><a href="">Historiques des Soumissions</a></li>
            <li><a href="">Historiques des Absences</a></li>
            <li><a href="">Informations Supplementaire</a></li>
        </ul>
    </div>
    </div>
</div>