<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'etudiant') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}
$title = "Etudiant";
use App\Connection;
use App\EtudiantTable;
use App\ClasseTable;


$pdo = Connection::getPDO(); 
$cin = $_SESSION['id_user'];
$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$etudiantTable = new EtudiantTable($pdo);
$classeTable = new ClasseTable($pdo);

$etudiant = $etudiantTable->findByCin($cin);
$filiere = $etudiantTable->getFiliere($cin); 

$idClasse = $etudiant->getIdClasse();
$classe = $classeTable->findById($idClasse);
$etudiantsDeMaClasse = $etudiantTable->getAllByClasse($idClasse);

?>

<div class="presence">
    <div class="intro">
        <h1>Etudiants de ma classe</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="presence-container">
        <section class="professor-info container">
            <div class="filiere-group">
                <span><?= $filiere ? $filiere->getNomFiliere() : 'non spécifié' ?></span>
            </div>
            <div class="classe-group">
                <span><?= $classe ? $classe->getNomClasse() : 'Non Specifié' ?></span>
            </div>
        </section>
        <?php if (!empty($etudiantsDeMaClasse)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $numero = 1 ?>
                <?php foreach ($etudiantsDeMaClasse as $e) : ?>
                <tr>
                    <td><?= $numero++ ?></td>
                    <td><?= htmlspecialchars($e->getNom()) ?></td>
                    <td><?= htmlspecialchars($e->getPrenom()) ?></td>
                    <td><?= htmlspecialchars($e->getEmail()) ?></td>
                </tr>
            <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else : ?>
            <p class="no-data">Aucune absence enregistrée.</p>
        <?php endif; ?>
    </div>
</div>
