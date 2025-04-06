

<?php
use App\Connection;
$pdo = Connection::getPDO();

?>
<div class="prof-list">
    <h1> Liste Des Professeurs</h1>
    <hr>
    <form action="" class="tri-list" method="POST">
        <select name="departement" id="tri">
            <option value="nom">All</option>
            <?php
            $sql = "SELECT * FROM departement";     
        </select>
        <input type="submit" value="Trier" name="submit">
    </form>
</div>