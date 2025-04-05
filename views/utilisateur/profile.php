<?php 

use App\Connection;
function encodindCIN($cin) {
    global $secretKey;
    return  hash_hmac('sha256', $cin, $secretKey);
}
 $pdo = Connection::getPDO();

 $sql = "SELECT * FROM utilisateur";
 $info = [];
 foreach($pdo->query($sql) as $row){
    echo "je viens \n tu viens";
    if ($_SESSION['id_user'] === encodindCIN($row['cin'])){
        foreach($row as $col=>$val){
            if (!is_integer($col)){
               $info[$col] = $val; 
            }  
        }
    }
 }

 ?>
<div id="interface">
    <div class="nomphoto">
        <div><img id="img" src=<?= "/images/".$info['photo'];?> alt="profil"></div>
        <div id="nom">
            <h3><?= $info['role'].' '.$info['nom'];?></h3>
        </div>
    </div>
    <div class="general">
        <div class="userInfo bloc1">
            <h3 class="titre">User Details</h3>
            <a href="#" class="edit">edit profile</a>
            <table class="info">
                <!--<tr>
                    <td>Responsabilite:</td>
                    <td> chef departement</td>
                </tr>
                <tr>
                    <td>userName:</td>
                    <td> Anthony</td>
                </tr>
                <tr>
                    <td>email:</td>
                    <td> dgfhrty@gmail.com</td>
                </tr>
                <tr>
                    <td>email:</td>
                    <td> dgfhrty@gmail.com</td>
                </tr>-->
                <?php
                    $tab = ['password','photo','id','role'];
                    foreach($info as $atribut=>$valeur){
                        if (!in_array($atribut,$tab)){?>
                            <tr>
                                <td><?= $atribut.': ';?></td>
                                <td><?= $valeur;?></td>
                            </tr><?php
                        }
                    }
                ?>
            </table>
        </div>
        <div class="bloc">
            <div class="userInfo bloc2">
                <h3 class="titre">Useful Links</h3>
                <p>calendrier</p>
                <p>listes des eleves</p>
                <p>Liste des etudiants</p>
            </div>
            <div class="userInfo bloc2">
                <h3 class="titre">Historiques</h3>
                <p>Historiques des soumissions</p>
                <p>listes des eleves</p>
                <p>Liste des etudiants</p>
            </div>
        </div>
    </div>
</div>