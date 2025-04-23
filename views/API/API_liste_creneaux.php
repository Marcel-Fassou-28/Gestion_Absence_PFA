<?php

use App\Connection;

$pdo = Connection::getPDO();

header('Content-Type: application/json');

$sql = "
    SELECT f.nomFiliere, f.idFiliere, c.nomClasse, c.idClasse FROM 
    Filiere f JOIN Classe c ON c.idFiliere = f.idFiliere
    ORDER BY f.nomFiliere, c.nomClasse
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($rows as $row) {
    $idFiliere = $row['idFiliere'];

    if (!isset($grouped[$idFiliere])) {
        $grouped[$idFiliere] = [
            'idFiliere' => $row['idFiliere'],
            'nomFiliere' => $row['nomFiliere'],
            'classes' => []
        ];
    }

    $grouped[$idFiliere]['classes'][] = [
        'idClasse' => $row['idClasse'],
        'nomClasse' => $row['nomClasse']
    ];
}

echo json_encode(array_values($grouped), JSON_PRETTY_PRINT);