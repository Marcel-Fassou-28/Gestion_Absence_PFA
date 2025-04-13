<?php

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$moisEnFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
    'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
    'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');
$dateSql = $date->format('Y-m-d H:i:s');

?>

<div class="presence">
    <div class="intro">
        <h1>Faire la présence</h1>
    </div>
    <div class="hr"></div>
    <div class="action-faire-presence">
        <div class="scanner-sheet">
            <button class="presence-list" data-modal-id="scanner-modal">
                Liste de présence
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="scanner-icon">
                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                    <line x1="8" y1="8" x2="16" y2="8"></line>
                    <line x1="8" y1="16" x2="16" y2="16"></line>
                </svg>
            </button>
        </div>
        <div class="effectuer-list">
            <button class="presence-btn" onclick="window.location.href='<?= $router->url('professor-presence') . '?use-link=student-presence' . '?redirect=1' ?>'">
                Faire la présence
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="list-icon">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <circle cx="4" cy="6" r="1"></circle>
                    <circle cx="4" cy="12" r="1"></circle>
                    <circle cx="4" cy="18" r="1"></circle>
                </svg>
            </button>
        </div>
    </div>
    <!-- Boîte modale pour le scanner -->
    <div class="modal-box" id="scanner-modal">
        <div class="modal-box-overlay"></div>
        <form class="modal-box-content" method="post" enctype="multipart/form-data">
            <h2>Liste de présence</h2>
            <div class="upload-desktop">
                <label for="presence-file">Uploader un fichier :</label>
                <input type="file" id="presence-file" accept=".pdf,image/*">
            </div>
            <div id="upload-mobile">
                <label for="presence-camera">Prendre une photo :</label>
                <input type="file" id="presence-camera" accept="image/*" capture="environment">
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-modal close-modal">Annuler</button>
                <button type="submit" class="btn-modal submit-presence">Valider</button>
            </div>
        </form>
    </div>
</div>
