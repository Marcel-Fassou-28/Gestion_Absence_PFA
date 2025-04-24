<?php

use App\Connection;

$pdo = Connection::getPDO();

if (isset($_GET['cinProf'])) {
    $cinProf = $_GET['cinProf'];
} else {
    $cinProf = null;
}

header('Content-Type: application/json');

$sql = 'SELECT f.idFiliere, f.nomFiliere, c.idClasse, c.nomClasse, m.idMatiere, m.nomMatiere
    FROM Filiere f JOIN Classe c ON f.idFiliere = c.idFiliere JOIN Matiere m ON c.idClasse = m.idClasse WHERE m.cinProf = :cinProf ORDER BY f.nomFiliere, c.nomClasse, m.nomMatiere
';

$stmt = $pdo->prepare($sql);
$stmt->execute(['cinProf' => $cinProf]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];

foreach ($rows as $row) {
    $idFiliere = $row['idFiliere'];
    $idClasse = $row['idClasse'];

    if (!isset($grouped[$idFiliere])) {
        $grouped[$idFiliere] = [
            'idFiliere' => $idFiliere,
            'nomFiliere' => $row['nomFiliere'],
            'classes' => []
        ];
    }

    if (!isset($grouped[$idFiliere]['classes'][$idClasse])) {
        $grouped[$idFiliere]['classes'][$idClasse] = [
            'idClasse' => $idClasse,
            'nomClasse' => $row['nomClasse'],
            'matieres' => []
        ];
    }

    $grouped[$idFiliere]['classes'][$idClasse]['matieres'][] = [
        'idMatiere' => $row['idMatiere'],
        'nomMatiere' => $row['nomMatiere']
    ];
}

// Transformation pour nettoyer les index numériques
$result = array_map(function ($filiere) {
    $filiere['classes'] = array_values($filiere['classes']);
    return $filiere;
}, array_values($grouped));

echo json_encode($result, JSON_PRETTY_PRINT);