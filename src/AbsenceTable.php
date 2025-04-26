<?php

namespace App;

use PDO;
use App\Model\Absence;
use DateTime;

class AbsenceTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAbsencesNonJustifiees(string $cin): array
    {
        $query = $this->pdo->prepare("
            SELECT * FROM absence 
            WHERE cin = :cin AND etat_justification != 'justifiée'
        ");
        $query->execute(['cin' => $cin]);

        $absences = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $absence = new Absence();
            $absence->setIDAbsence((int) $row['id_absence']);
            $absence->setDate(new DateTime($row['date_absence']));
            $absence->setIDEtudiant($row['cin']);
            $absence->setIDMatiere((int) $row['id_matiere']);
            $absences[] = $absence;
        }

        return $absences;
    }

    public function justifierAbsence(int $idAbsence, string $etat = 'justifiée'): bool
    {
        $query = $this->pdo->prepare("UPDATE absence SET etat_justification = :etat WHERE id_absence = :id");
        return $query->execute([
            'etat' => $etat,
            'id' => $idAbsence
        ]);
    }
}
