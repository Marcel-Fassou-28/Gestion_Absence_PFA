<?php
use App\Connection;
use App\EtudiantTable;
use App\ClasseTable;

$pdo = Connection::getPDO(); 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'etudiant') {
    header('Location: ' . $router->url('accueil'));
    exit();
}

$cin = $_SESSION['id_user'];

$etudiantTable = new EtudiantTable($pdo);
$classeTable = new ClasseTable($pdo);

$etudiant = $etudiantTable->findByCin($cin);

if (!$etudiant) {
    echo "Étudiant non trouvé.";
    exit();
}

$idClasse = $etudiant->getIdClasse();
$classe = $classeTable->findById($idClasse);
$etudiantsDeMaClasse = $etudiantTable->getAllByClasse($idClasse);
?>
<div class="dashboard-messagerie container">
    <h2 class="messagerie-intro">Élèves de ma classe : <?= htmlspecialchars($classe->getNomClasse()) ?></h2>
    
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiantsDeMaClasse as $e) : ?>
                <tr>
                    <td><?= htmlspecialchars($e->getNom()) ?></td>
                    <td><?= htmlspecialchars($e->getPrenom()) ?></td>
                    <td><?= htmlspecialchars($e->getEmail()) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
