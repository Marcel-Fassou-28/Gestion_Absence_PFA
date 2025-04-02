<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION)) {
    $role = $_SESSION['role'];
    $id = $_SESSION['id_user'];
    $name = $_SESSION['username'];

    switch ($role) {
        case 'admin':
            header('Location:' . $router->url('administrator',  ['name' => $name ,'id' => $id]));
            exit();
            break;
        case 'professeur':
            header('Location:' . $router->url('professor',  ['name' => $name ,'id' => $id]));
            exit();
            break;
        case 'etudiant':
            header('Location:' . $router->url('student', ['name' => $name ,'id' => $id]));
            exit();
            break;
    }
}
