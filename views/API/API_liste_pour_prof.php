<?php

use App\Connection;

$pdo = Connection::getPDO();

header('Content-Type: application/json');

$sql = "
    SELECT d.idDepartement, d.nomDepartement, f.nomFiliere, f.idFiliere, c.nomClasse, c.idClasse 
    FROM departement d JOIN Filiere f ON d.idDepartement = f.idDepartement JOIN Classe c 
    ON c.idFiliere = f.idFiliere ORDER BY d.nomDepartement, f.nomFiliere, c.nomClasse
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($rows as $row) {
    $idDep = $row['idDepartement'];
    $idFiliere = $row['idFiliere'];

    if (!isset($grouped[$idDep])) {
        $grouped[$idDep] = [
            'idDepartement' => $idDep,
            'nomDepartement' => $row['nomDepartement'],
            'filieres' => []
        ];
    }

    if (!isset($grouped[$idDep]['filieres'][$idFiliere])) {
        $grouped[$idDep]['filieres'][$idFiliere] = [
            'idFiliere' => $idFiliere,
            'nomFiliere' => $row['nomFiliere'],
            'classes' => []
        ];
    }

    $grouped[$idDep]['filieres'][$idFiliere]['classes'][] = [
        'idClasse' => $row['idClasse'],
        'nomClasse' => $row['nomClasse']
    ];
}

// Réindexer correctement (car les clés des filières sont associatives)
$result = array_map(function ($dep) {
    $dep['filieres'] = array_values($dep['filieres']);
    return $dep;
}, array_values($grouped));

echo json_encode($result, JSON_PRETTY_PRINT);