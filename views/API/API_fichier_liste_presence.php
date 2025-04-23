<?php

use App\Connection;

$pdo = Connection::getPDO();

header('Content-Type: application/json');

$sql = "
    SELECT 
        Classe.idClasse,
        Classe.nomClasse,
        Matiere.idMatiere,
        Matiere.nomMatiere
    FROM 
        Classe
    JOIN 
        Matiere ON Classe.idClasse = Matiere.idClasse
    ORDER BY 
        Classe.nomClasse, Matiere.nomMatiere
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
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
