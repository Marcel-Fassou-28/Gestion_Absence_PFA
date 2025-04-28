<?php
if(!isset($_SESSION['id_user'])) {
    header('location: ' .$router->url('accueil'));
    exit();
}

if(isset($_SESSION['id_user']) && $_SESSION['role'] != 'professeur') {
    header('location: ' . $router->url('user-dashboard' , ['role' => $_SESSION['role']]));
    exit();
}


use App\Connection;
use App\Professeur\ProfessorTable;
use App\Professeur\CurrentInfo;
use App\Model\ListePresence;

$cinProf = $_SESSION['id_user'];
$pdo = Connection::getPDO();
$tableProf = new ProfessorTable($pdo);
$tableProfCurrent = new CurrentInfo($pdo);
$creneau = $tableProfCurrent->getCurrentCreneau($cinProf);
$error = null;

if (!isset($creneau)) {
    $error = 1;
    header('location: '. $router->url('user-dashboard', ['role' => $_SESSION['role']]) .'?error_prof=' . $error);
    exit();
}

/*
if (!$tableProfCurrent->hasAlreadySendListPresence($cinProf)) {
    $error = 1;
    header('location: '. $router->url('add-presence') .'?use-link=student-presence&should_submit=' . $error);
    exit();
}

*/

$classe = $tableProfCurrent->getCurrentClasse($cinProf)->getNomClasse();
$listPresence = new ListePresence();


$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$moisEnFrancais = [
    'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
    'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
    'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
];
$moisAnglais = $date->format('F');
$dateDuJour = $date->format('d') . ' ' . $moisEnFrancais[$moisAnglais] . ' ' . $date->format('Y');
$dateSql = $date->format('Y-m-d H:i:s');
$errorMessage = "";
$success = null;

?>

<div class="presence">
    <?php if(isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success">
            Votre fichier à été emis avec success
        </div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == '0'): ?>
        <div class="alert alert-danger">
            Votre fichier n'a pas été émis
        </div>
    <?php else: ?><?php endif ?>
    <div class="intro">
        <h1>Faire la présence</h1>
    </div>
    <div class="hr"></div>
    <div class="action-faire-presence">
        <div class="scanner-sheet">
            <button class="presence-list" onclick="window.location.href='<?= $router->url('add-presence') . '?use-link=student-presence' . '?redirect=1' ?>'" data-modal-id="scanner-modal">
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
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-primary">Votre message a été avec succès</div>
        <?php endif ?>
    </div>
    <div class="documentation">
        <div class="documentation-intro" >
            <h1>documentation</h1>
        </div>
        <div class="hr"></div>
        <div class="documentation-container">
        <section class="container how-to-scan">
            <h2>Comment Scanner</h2>
            <div class="hr"></div>
            <div class="use-info">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dicta quidem ab iste vitae aut harum laudantium illum quas. Itaque ullam ipsa tempora modi ab maiores! Fuga ad, porro cumque, quas saepe placeat in eligendi labore vero provident minus, odit dolores vel possimus ab ea unde! Nemo vel quas totam in.
            </div>
            
        </section>
        <section class="container how-to-check">
            <h2>Effectuer la présence</h2>
            <div class="hr"></div>
            <div class="use-info">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, natus quam! Aspernatur omnis rerum libero voluptate, perferendis perspiciatis ex cumque praesentium, quo odit alias? Sapiente aspernatur itaque beatae eius minima vitae! Delectus rerum officiis nisi porro. Tempora omnis commodi dicta nesciunt reiciendis, minus voluptatum quidem quis esse dignissimos quisquam laboriosam veritatis. Possimus fuga ex, vero quo assumenda vel architecto unde sed veniam veritatis quam quisquam laboriosam, laudantium inventore quibusdam atque voluptate quasi quis recusandae eius dicta tenetur enim delectus. Quidem reprehenderit ad provident nulla quas pariatur aspernatur nam suscipit numquam molestias temporibus vitae unde, repudiandae eos voluptate culpa sunt ex?
            </div>

        </section>
        </div>
    </div>
</div>
