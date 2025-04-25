<?php
namespace APP;
use App\Connection;
use App\Admin\adminTable;


require dirname(__DIR__, 4) . '/vendor/setasign/fpdf/fpdf.php';

class PDF extends \FPDF
{
    private $matiere;
    
    function __construct($matiere)
    {
        parent::__construct();
        $this->matiere = $matiere;
    }
    
    // En-tête du tableau
    
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, mb_convert_encoding('Liste des etudiants privees de passer l\'examen', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        // Sous-titre avec la matière
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, mb_convert_encoding('Matiere : ' . $this->matiere, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        // Date
        $this->SetFont('Arial', 'I', 10);
        $date = new \DateTime('now', new \DateTimeZone('Africa/Casablanca'));
        $this->Cell(0, 6, $date->format('Y-m-d H:i'), 0, 1, 'R');
        
        // En-têtes du tableau
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(0, 0, 200);
        $this->Cell(20, 7, mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Cell(60, 7, mb_convert_encoding('Nom', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Cell(60, 7, mb_convert_encoding('Prenom', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Cell(50, 7, mb_convert_encoding('CNE', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);
    }
    
    // Pied de page
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

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
    $pdf->Cell(60, 6, mb_convert_encoding($etudiant->getNom(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
    $pdf->Cell(60, 6, mb_convert_encoding($etudiant->getPrenom(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
    $pdf->Cell(50, 6, mb_convert_encoding($etudiant->getCNE(), 'ISO-8859-1', 'UTF-8'), 1, 1, 'L');
    
    // Ajout automatique de page si nécessaire
    if ($pdf->GetY() > 270) {
        $pdf->AddPage();
    }
}
//$pdf->Output();
 // Ajoute cette ligne pour éviter tout texte parasite après le PDF

// Génération du PDF
$pdf->Output('D', 'Liste_Etudiants_' . $matiere . '.pdf'); // D pour téléchargement forcé
exit;