<?php

use App\Connection;

$pdo = Connection::getPDO();

$cinProf = $_GET['cinProf'] ?? null;

header('Content-Type: application/json');

$sql = '
    SELECT DISTINCT c.idClasse, c.nomClasse
    FROM Classe c
    INNER JOIN Matiere m ON c.idClasse = m.idClasse
    WHERE m.cinProf = :cinProf
    ORDER BY c.nomClasse
';

$stmt = $pdo->prepare($sql);
$stmt->execute(['cinProf' => $cinProf]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows, JSON_PRETTY_PRINT);
