<?php

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');

?>
<div class="prof-list">
<div class="intro-prof-list">
        <h1> Liste Des Créneaux</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
        <div class="form-ajout">
            <a href="ajouter-Etudiant?modif=1" class="btn-ajout">Ajouter un Créneau</a>
        </div>
    </div>
    <div class="hr"></div>
    <div class="form-tri-container">
        <form action="" class="tri-list container" method="POST">
            <div class="list-filiere">
            <select name="filiere" id="tri" onchange="this.form.submit()">
                <option value="defaut">Filières</option>
                <!-- Filières -->
            </select>
            </div>
            <div class="list-classe">
            <select name="classe" id="tri">
                <option value="defaut">Classe</option>
                <!-- Classe -->
            </select>
            </div>
            <div class="submit-group">
            <input class="submit-btn" type="submit" value="Trier" name="submit">
            </div>

        </form>
    </div>

    <div class="list-tri-table">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Créneau</th>
                    <th>Classe</th>
                    <th>Professeur en Charge</th>
                    <th>CIN</th>
                    <th>Actions</th>
                </tr>
            </thead>

        </table>
    </div>
</div>