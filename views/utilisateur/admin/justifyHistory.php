<?php
namespace APP;
use App\Connection;
use App\adminTable;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeJustificatif = $list->getAllJustificatif();
?>

<div id="interface">
        <div id="liste">
            <h1 id="titre">Historiques des justificatifs</h1>
            <hr>

            <div id="justificatif">
                <table id="tables">
                    <thead>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Date de soumission</th>
                        <th>Action</th>
                    </thead>
                    <?php 
                       foreach($listeJustificatif as $row){?>
                       <tr><?php
                        foreach($row as $col => $val){
                    
                            if(!is_integer($col)){
                        ?>
                        <td><?= htmlspecialchars($val);}}?></td>
                        <td><button><a href="">Details</a></button></td>
                        </tr><?php
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>