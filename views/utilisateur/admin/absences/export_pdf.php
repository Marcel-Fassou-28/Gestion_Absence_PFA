<?php
namespace APP;
use App\Connection;
use App\Admin\adminTable;
use App\PDF;



if (!isset($_GET['matiere']) || empty($_GET['matiere'])) {
    die("Erreur : Matière non spécifiée.");
}

$matiere = $_GET['matiere'];

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

// pour recupere  l'ID de la matière
$idMatiere = $list->getIdMatiereByName($matiere);

// extraire  tous les étudiants 
$listeEtudiant = $list->getPrivateStudentToPastExamByMatiere($idMatiere);

// Création du PDF
$pdf = new PDF($matiere);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Remplissage du tableau
$count = 1;
foreach ($listeEtudiant as $etudiant) {
    $pdf->Cell(20, 6, $count++, 1, 0, 'C');
    $pdf->Cell(60, 6, mb_convert_encoding($etudiant->getNom(). ' '. $etudiant->getPrenom(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    
    $pdf->Cell(40, 6, mb_convert_encoding($etudiant->getCNE(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell(70, 6, mb_convert_encoding($etudiant->getEmail(), 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
    // Ajout automatique de page si nécessaire
    if ($pdf->GetY() > 270) {
        $pdf->AddPage();
    }
}


// Génération du PDF
$pdf->Output('D', 'Liste_Etudiants_' .mb_convert_encoding( $matiere, 'ISO-8859-1', 'UTF-8') . '.pdf'); // D pour telecharger le fichier d'abord
exit;