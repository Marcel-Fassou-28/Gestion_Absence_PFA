<?php

namespace App;

use PDO;

class JustificatifTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function ajouterJustificatif(int $idAbsence, string $nomFichier): bool
    {
        $query = $this->pdo->prepare("
            INSERT INTO justificatif (id_absence, nom_justificatif, date_ajout)
            VALUES (:id_absence, :nom_justificatif, NOW())
        ");
        return $query->execute([
            'id_absence' => $idAbsence,
            'nom_justificatif' => $nomFichier
        ]);
    }

    public function getJustificatifParAbsence(int $idAbsence): ?string
    {
        $query = $this->pdo->prepare("SELECT nom_justificatif FROM justificatif WHERE id_absence = :id");
        $query->execute(['id' => $idAbsence]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['nom_justificatif'] : null;
    }
}
