<?php

use App\Connection;
use App\Admin\adminTable;
use App\Mailer;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);
$idMatiere = $_GET['matiere'] ?? null;



//script pour notifier l'etudiant lorsqu'il aura depassee 6h d'absences via mail\

if (isset($idMatiere)) {
    $course = $list->getMatiereById($idMatiere)->getNomMatiere();
    $classe = $list->findClassByMatiere($course);
    $listeEtudiant = $list->getStudentByClass($classe, );
    foreach ($listeEtudiant as $row) {
        $nbreAbsences = $list->getAbsenceStudentByMatiere($row->getCIN(), $idMatiere);
        if ($nbreAbsences >= 6) {
            $mail = new Mailer();
            $email = $row->getEmail();
            $name = $row->getNom() . ' ' . $row->getPrenom();
            $mail->absenceAlertMail($email, $name, $course, $nbreAbsences);
        }
    }
    $notifier = 1;
    header('Location: '.$router->url('historikAbscences').'?listprof=1&p=0&notifier='.$notifier);
    exit();
}

?>