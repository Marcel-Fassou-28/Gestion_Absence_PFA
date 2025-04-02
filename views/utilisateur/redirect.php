<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION)) {
    $role = $_SESSION['role'];

    switch ($role) {
        case 'admin':
            header('Location:' . $router->url('administrator-home'));
            exit();
            break;
        case 'professeur':
            header('Location:' . $router->url('professor-home'));
            exit();
            break;
        case 'etudiant':
            header('Location:' . $router->url('student-home'));
            exit();
            break;
    }
}
