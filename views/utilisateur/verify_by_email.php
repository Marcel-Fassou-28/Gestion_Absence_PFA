<?php

use App\Connection;
use App\Model\Utilisateur;
use App\UserTable;
use App\Mailer;


$pdo = Connection::getPDO();
$userTable = new UserTable($pdo);
$mailer = new Mailer();

if(!empty($_GET) && isset($_GET['token']) && $_GET['email']) {
    $token = urldecode($_GET['token']) ?? '';
    $email = urldecode($_GET['email']) ?? '';
    $emailSanitize = filter_var($email, FILTER_VALIDATE_EMAIL);

    $query = $pdo->prepare('SELECT * FROM utilisateur WHERE token = :token');
    $query->execute([
        'token' => $token
    ]);
    $query->setFetchMode(\PDO::FETCH_CLASS, Utilisateur::class);
    $result = $query->fetch();

    if ($result && $emailSanitize != $result->getEmail()) {
        $query1 = $pdo->prepare('UPDATE utilisateur SET email = :email WHERE token = :token');
        $query1->execute([
            'email' => $emailSanitize,
            'token' => $token
        ]);
        $query2 = $pdo->prepare('UPDATE utilisateur SET token = NULL WHERE email = :email');
        $query2->execute([
            'email' => $emailSanitize
        ]);
        
        $user = $userTable->findByEmail($emailSanitize);
        $mailer->emailChangedConfirmationMail($emailSanitize, $user->getNom() . ' ' . $user->getPrenom());
        if ($user) {
            session_start();
            $_SESSION['id_user'] = $user->getCIN();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['username'] = pascalCase($user->getNom() . ' ' .$user->getPrenom());
            
            header('Location:' . $router->url('user-home', ['role' =>$user->getRole(), 'id' => encodindCIN($user->getCIN())]));
            exit();
        } else {
            header('Location:' . $router->url('accueil'));
            exit();
        }
    } else {
        header('Location:' . $router->url('accueil'));
        exit();
    }

    
}