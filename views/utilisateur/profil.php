<?php 

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

use App\Connection;
use App\UserTable;
use App\Professeur\ProfessorTable;
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
        <img src="<?= $router->url('serve-photo', ['role'=> $_SESSION['role'],'id'=> $_SESSION['id_user']]).'?id_user='.$_SESSION['id_user'] ?>" alt="Photo de profil de <?= htmlspecialchars($user->getNom()) ?>">
        <h3><?= htmlspecialchars($user->getRole() . ' ' . $user->getNom()) ?></h3>
    </div>
    <div class="container-useful">
        <div class="info-section">
            <h3>Vos Informations</h3>
            <a href="<?= $router->url('edit-profil', ['role' => $user->getRole(), 'id' =>$user->getCIN()]) . '?edit_profil=1' ?>" target="_blank" class="edit-profile">Modifier le profil</a>
            <div class="personal-info">
                <div>
                    <p>Email:</p>
                    <p><?= htmlspecialchars($user->getEmail()) ?></p>
                </div>
                <div>
                    <p>Username:</p>
                    <p><?= htmlspecialchars($user->getUsername()) ?></p>
                </div>
                <div>
                    <p>CIN:</p>
                    <p><?= htmlspecialchars($user->getCIN()) ?></p>
                </div>
                <div>
                    <p>Nom:</p>
                    <p><?= htmlspecialchars($user->getNom()) ?></p>
                </div>
                <div>
                    <p>Prénom:</p>
                    <p><?= htmlspecialchars($user->getPrenom()) ?></p>
                </div>
                <?php if ($_SESSION['role'] === 'professeur'): ?>
                    <div>
                        <p>Matières enseignées:</p>
                        <p><?php foreach ($academic_info['matiere'] as $matiere) {
                            echo htmlspecialchars($matiere->getNomMatiere()) . ', ';
                        } ?></p>
                    </div>
                <?php endif ?>
                <?php if ($_SESSION['role'] === 'etudiant'): ?>
                    <div>
                        <p>Filière:</p>
                        <p><?= htmlspecialchars($studentFiliere->getNomFiliere()) ?></p>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <?php if ($_SESSION['role'] != 'admin'): ?>
            <div class="academic-section">
                <div class="departement">
                    <?php if ($_SESSION['role'] === 'professeur'): ?>
                        <p>Département:</p>
                        <p><?= htmlspecialchars($academic->getDepartementProf($_SESSION['id_user'])->getNomDepartement()) ?></p>
                    <?php elseif ($_SESSION['role'] === 'etudiant'): ?>
                        <p>Département:</p>
                        <p><?= htmlspecialchars($student->getDepartementEtudiant($_SESSION['id_user'])->getNomDepartement()) ?></p>
                    <?php endif ?>
                </div>
                <?php if ($_SESSION['role'] === 'professeur'): ?>
                    <div class="filiere">
                        <p>Filière d'affectation:</p>
                        <p><?php foreach ($academic_info['filiere'] as $filiere) {
                            echo htmlspecialchars($filiere->getNomFiliere()) . ', ';
                        } ?></p>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>
        <div class="useful-link-section">
            
            <h3>Liens Utiles</h3>
            <ul>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="<?= $router->url('liste-etudiants').'?listprof=1&p=0'?>">Listes des étudiants</a></li>
                <?php elseif($_SESSION['role'] === 'professeur'): ?>
                    <li><a href="<?= $router->url('professor-listeEtudiant') . '?use-link=student-list'?>">Listes des étudiants</a></li>
                <?php elseif($_SESSION['role'] === 'etudiant'): ?>
                    <li><a href="<?= $router->url('etudiant-messagerie').'?messagerie=1'?>">Ma messagerie</a></li>
                <?php endif ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="<?=$router->url('liste-professeur').'?listprof=1&p=0'?>">Liste des professeurs</a></li>
                <li><a href="<?=$router->url('RecapAbsences').'?listprof=1'.'&justifier=1'?>">Recapitulatif des Absences</a></li>
                <?php endif ?>
            </ul>
        </div>
        <div class="historic-section">
            <h3>Historiques</h3>
            <ul>
                <li><a href="<?php if ($_SESSION['role'] === 'admin') {echo $router->url('historikAbscences').'?listprof=1';}?>">Historiques des soumissions</a></li>
                <?php if ($_SESSION['role'] === 'admin') :?>
                    <li><a href="<?=  $router->url('justification').'?listprof=1'.'&justifier=1';?>">Historiques des justificatifs</a></li>
                    <li><a href="<?=  $router->url('etudiantprivee').'?listprof=1'.'&justifier=1';?>">Liste des etudiants Privés d'examen</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>