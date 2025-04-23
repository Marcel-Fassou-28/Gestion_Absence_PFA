<?php

use App\Connection;

$pdo = Connection::getPDO();

if (isset($_GET['cinProf'])) {
    $cinProf = $_GET['cinProf'];
} else {
    $cinProf = null;
}

header('Content-Type: application/json');

$sql = 'SELECT c.idClasse, c.nomClasse, m.idMatiere, m.nomMatiere
    FROM Classe c JOIN Matiere m ON c.idClasse = m.idClasse WHERE m.cinProf = :cinProf ORDER BY c.nomClasse, m.nomMatiere
';

$stmt = $pdo->prepare($sql);
$stmt->execute(['cinProf' => $cinProf]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($rows as $row) {
    $idClasse = $row['idClasse'];
    
    if (!isset($grouped[$idClasse])) {
        $grouped[$idClasse] = [
            'idClasse' => $idClasse,
            'nomClasse' => $row['nomClasse'],
            'matieres' => []
        ];
    }

    $grouped[$idClasse]['matieres'][] = [
        'idMatiere' => $row['idMatiere'],
        'nomMatiere' => $row['nomMatiere']
    ];
}

echo json_encode(array_values($grouped), JSON_PRETTY_PRINT);