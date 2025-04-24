<?php

use App\Connection;

$pdo = Connection::getPDO();

header('Content-Type: application/json');

$sql = "
    SELECT n.idNiveau, n.nomNiveau, f.nomFiliere, f.idFiliere FROM 
    Niveau n JOIN Classe c ON n.idNiveau = c.idNiveau JOIN Filiere f ON f.idFiliere = c.idFiliere
    ORDER BY n.nomNiveau, f.nomFiliere
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($rows as $row) {
    $idNiveau = $row['idNiveau'];

    if (!isset($grouped[$idNiveau])) {
        $grouped[$idNiveau] = [
            'idNiveau' => $row['idNiveau'],
            'nomNiveau' => $row['nomNiveau'],
            'filieres' => []
        ];
    }

    $grouped[$idNiveau]['filieres'][] = [
        'idFiliere' => $row['idFiliere'],
        'nomFiliere' => $row['nomFiliere']
    ];
}

echo json_encode(array_values($grouped), JSON_PRETTY_PRINT);