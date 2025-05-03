<?php

if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header('location: ' .$router->url('user-home', ['role' => $_SESSION['role']]));
    exit();
}



use App\Connection;
use App\Admin\adminTable;

$pdo = Connection::getPDO();
$result = new adminTable($pdo);

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d');

$id = $_GET['idjustificatif'];
$justificatif = $result->getinfoJustificatifById($id);
$idjus = $justificatif->getIdJustificatif();

?>
<div class=" msg prof-list">
    <div class="intro-prof-list">
        <h1>Details du Justificatif</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>

    <div class="hr"></div>
    <div class="presence-file">
        <div class="presence-file-show">

            <img src="<?= $router->url('serve-justificatif') . '?fichier=' . htmlspecialchars($justificatif->getnomFichierJustificatif()); ?> "
                alt="justificatif">
        </div>
    </div>
    <div class="description">
        <h3>DESCRIPTION</h3>
        <div class="hr"></div>
        <p><?= $justificatif->getMessage(); ?></p>

        <?php

        if (isset($_GET['traite']) && $_GET['traite'] === '1') {
            $id = $_GET['idjustificatif'];
            $justificatif = $result->getinfoJustificatifById($id);

            $idAbscence = $justificatif->getIdAbsence();

            $cinEtudiant = $pdo->query("SELECT cinEtudiant FROM absence WHERE idAbsence = $idAbscence")->fetchColumn();


            if (isset($_POST['debut']) && isset($_POST['fin'])) {
                $debut = $_POST['debut'];
                $fin = $_POST['fin'];

                if ($result->justifierAbscence($id, $cinEtudiant, $debut, $fin)) {
                    header('Location: ' . $router->url('justification') . '?listprof=1&p=0');
                    exit();
                }
            }

            ?>


            <div class="intro-prof-list titre">
                <h3> VALIDATION DU JUSTIFICATIF</h3>
            </div>
            <div class="hr"></div>
            <div id="form" class="form-modifie-container global">
                <form action="" class="creneau-modifie container" method="POST">
                    <section class="edit-creneau-section">
                        <div class="creneau-debut">
                            <label for="cin">Date De Debut</label>
                            <input type="date" name="debut" value="" max="<?= $dateSql; ?>" required>
                        </div>
                        <div class="creneau-fin">
                            <label for="nom">Date de fin</label>
                            <input type="date" name="fin" value="" max="<?= $dateSql; ?>" required>
                        </div>
                        <section class="submit-group-creneau">
                            <button type="submit" class="submit-btn">Sauvegarder</button>
                            <button class="btn2 submit-btn"
                                onclick="window.location.href='<?= $router->url('detail_justificatif') . '?listprof=1&p=0' . '&idjustificatif=' . $id; ?>'">Annuler</button>
                        </section>


                </form>
            </div>
        </div>


        <?php
        }
        ?>

    <div class="traitement">
        <?php if (!isset($_GET['traite'])):
            if ($justificatif->getStatut() === 'en attente'): ?>

                <a href="<?= $router->url('detail_justificatif') . '?listprof=1' . '&traite=1' . '&idjustificatif=' . $idjus; ?>"
                    class="btn1 submit-btn">traiter</a>
                <a href="<?= $router->url('rejecter_justificatif') . '?listprof=1' . '&idjustificatif=' . $idjus; ?>"
                    class="btn2 submit-btn">Rejeter</a>
                <?php
            else: ?>
                <a href="<?= $router->url('supprimer_justificatif') . '?listprof=1' . '&idjustificatif=' . $idjus; ?>"
                    class="btn2 submit-btn">Supprimer</a>

            <?php endif;
        else: ?>




        <?php endif; ?>

    </div>
</div>





<style>
    .valide {
        width: 100%;
        margin: auto;
    }

    .titre {
        padding-top: 0.5rem;

        margin-bottom: -0.7rem;
    }



    .msg .description {
        width: 87%;
        padding: 20px;
        background-color: #1c1d1e88;
        border-radius: 12px;
        margin: auto;
        font-weight: 600;

    }

    .traitement {
        display: flex;
        justify-content: center;
        gap: 1rem;

        padding-top: 1rem;
        width: 60%;
        margin: auto;
    }

    .submit-btn {
        text-align: center
    }

    @media screen and (max-width : 600px) {
        .traitement {
            flex-direction: column;
        }
    }
</style>