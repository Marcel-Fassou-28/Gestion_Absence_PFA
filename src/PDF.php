<?php

namespace App;

use FPDF\FPDF;
class PDF extends \FPDF
{
    private $classe;
    
    function __construct($classe)
    {
        parent::__construct();
        $this->classe = $classe;
    }
    
    // En-tête du tableau
    
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, mb_convert_encoding('Liste des etudiants privees de passer l\'examen', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        // Sous-titre avec la matière
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, mb_convert_encoding('Classe : ' . $this->classe, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        // Date
        $this->SetFont('Arial', 'I', 10);
        $date = new \DateTime('now', new \DateTimeZone('Africa/Casablanca'));
        $this->Cell(0, 6, $date->format('d-m-Y'), 0, 1, 'R');
        
        // En-têtes du tableau
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(140, 176, 240);
        $this->SetTextColor(255,255,255);
        $this->Cell(20, 7, mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Cell(60, 7, mb_convert_encoding('Nom', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        
        $this->Cell(40, 7, mb_convert_encoding('CNE', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $this->Cell(70, 7, mb_convert_encoding('Matieres', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', true);
    }
    
    // Pied de page
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

?>