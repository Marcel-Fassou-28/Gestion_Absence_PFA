<?php

use App\Connection;
use App\Admin\adminTable;
use App\Mailer;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);
$mail = new Mailer();
$idMatiere = $_GET['matiere'] ?? null;
$isEtudiantPrivee = $_GET['privee'] ?? null;



//script pour notifier l'etudiant lorsqu'il aura depassee 6h d'absences via mail\

if (isset($idMatiere) && !isset($isEtudiantPrivee)) {
    $course = $list->getMatiereById($idMatiere)->getNomMatiere();
    $classe = $list->findClassByMatiere($course);
    $listeEtudiant = $list->getStudentByClass($classe);
    
    foreach ($listeEtudiant as $row) {
        $nbreAbsences = $list->getAbsenceStudentByMatiere($row->getCIN(), $idMatiere);
        if ($nbreAbsences >= 6) {
           
            $email = $row->getEmail();
            $name = $row->getNom() . ' ' . $row->getPrenom();
           
            $mail->absenceAlertMail($email, $name, $course, $nbreAbsences);
            
        }
    }
    $notifier = 1;
    header('Location: '.$router->url('historikAbscences').'?listprof=1&p=0&notifier='.$notifier);
    exit();
}

    
   

$classe = $_GET['classe'];
if (isset($classe) && isset($isEtudiantPrivee)) {
    $etudiant = $list->getStudentByClass($classe);
    $listeMatiere = $list->getMatiereByClass($classe);
    $absence = [];
    
    foreach ($etudiant as $etu):
        $course = '';
        
        foreach( $listeMatiere as $mat):
            
            if ( $list->getAbsenceStudentByMatiere($etu->getCIN(),$mat->getIDMatiere()) > 6):
                $absence[$etu->getIdEtudiant()][$mat->getNomMatiere()] = $list->getAbsenceStudentByMatiere($etu->getCIN(),$mat->getIDMatiere());
                $course = $course === '' ? $course : $course.', ';
                $course .= $mat->getNomMatiere();
               
            endif;
        endforeach;
       
        
        if ( !empty($absence[$etu->getIdEtudiant()])):
            
            
            $email = $etu->getEmail();
            $name = $etu->getNom() . ' ' . $etu->getPrenom();
            $mail->alertEtudiantPrivee($email, $name, $course);

        endif;
    endforeach;

    $notifier = 1;
    header('Location: '.$router->url('etudiantprivee') . '?listprof=1' . '&justifier=1' . '&p=0&notifier='.$notifier);
    exit();
}

?>