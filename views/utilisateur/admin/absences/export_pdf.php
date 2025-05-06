<?php
namespace APP;

if (!isset($_SESSION['id_user'])) {
    header('location: ' . $router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' . $router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}



use App\Connection;
use App\Admin\adminTable;
use App\PDF;



if (!isset($_GET['classe']) || empty($_GET['classe'])) {
    die("Erreur : classe non spécifiée.");
}

$classe = $_GET['classe'];

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

// pour recupere  l'ID de la matière


// extraire  tous les étudiants 
$etudiants = $list->getStudentByClass($classe);
$listeMatiere = $list->getMatiereByClass($classe);
$idClasse = $list->getIdClasseByClasseName($classe);

$absence = [];
foreach ($etudiants as $etu):

    foreach ($listeMatiere as $mat):
        if ($list->getAbsenceStudentByMatiere($etu->getCIN(), $mat->getIDMatiere()) > 6):
            $absence[$etu->getIdEtudiant()][$mat->getNomMatiere()] = $list->getAbsenceStudentByMatiere($etu->getCIN(), $mat->getIDMatiere());
        endif;
    endforeach;
endforeach;

// Création du PDF
$pdf = new PDF($classe);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Remplissage du tableau
$count = 1;
foreach ($etudiants as $etudiant) {
    if (!empty($absence[$etudiant->getIdEtudiant()])) {
        $mat = '';
        foreach($absence[$etudiant->getIdEtudiant()] as $col=>$val):
            $mat .= $col."\n";
        endforeach;


        $startY = $pdf->GetY(); 
        $pdf->setX($pdf->getX() + 120);
        $pdf->MultiCell(70, 6, mb_convert_encoding($mat, 'ISO-8859-1', 'UTF-8'), 1, 'C'); 
        $endY = $pdf->GetY(); 
        $lineHeight = $endY - $startY; //hauteur pour alignee les cellules

        $pdf->SetY($startY);
        //$pdf->setX(0);
        // revenieir au debut pour mettre les autre cellules avant les matieres
        //$pdf->SetXY($pdf->GetX() + 70, $startY);

        
        $pdf->Cell(20, $lineHeight, $count++, 1, 0, 'C'); // Colonne N°
        $pdf->Cell(60, $lineHeight, mb_convert_encoding($etudiant->getNom() . ' ' . $etudiant->getPrenom(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C'); // Colonne Nom
        $pdf->Cell(40, $lineHeight, mb_convert_encoding($etudiant->getCNE(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C'); // Colonne CNE
        //$pdf->MultiCell(70, 6, mb_convert_encoding($mat, 'ISO-8859-1', 'UTF-8'), 1, 'C'); 
        
        $pdf->SetY($endY);}

    // Ajout automatique de page si nécessaire
    if ($pdf->GetY() > 270) {
        $pdf->AddPage();
    }
}


// Génération du PDF
$pdf->Output('D', 'Liste_Etudiants_' . mb_convert_encoding($classe, 'ISO-8859-1', 'UTF-8') . '.pdf'); // D pour telecharger le fichier d'abord
exit;