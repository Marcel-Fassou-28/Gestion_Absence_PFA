<?php
namespace APP;
use App\Connection;
use App\adminTable;

$pdo = Connection::getPDO();
$list = new adminTable($pdo);

$listeJustificatif = $list->getAllJustificatif();

?>



<div class="global">
    <div id="liste">
        <h1 id="titre">Historiques des justificatifs</h1>
        <hr>

        <div class="list-tri justificatifs">
            <table class="prof-table">
                <thead class="heads">
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Date de soumission</th>
                    <th>Action</th>
                </thead>
                <?php
                foreach ($listeJustificatif as $row) { ?>
                    <tr><?php
                    foreach ($row as $col => $val) {

                        if (!is_integer($col)) {
                            ?>
                                <td><?= htmlspecialchars($val);
                        }
                    } ?></td>
                        <td><button class="btn1"><a href="">Details</a></button></td>
                    </tr><?php
                }
                ?>
            </table>
        </div>
    </div>
</div>