<?php
session_start();
require_once 'src/controller/ScheduleController.php';
require_once 'src/controller/HomeController.php';
require_once 'src/controller/choixRoueController.php';


$action = $_GET['action'] ?? 'default';

switch ($action) {

    case 'home':
        $controller = new HomeController();
        $controller->displayHome();
        break;
    case 'roueDeChoix':
        $controller = new choixRoueController();
        $controller->displayRoue();
        break;
    case 'but1':
        $controller = new choixRoueController();
        $controller->displayBut1();
        break;
    case 'but2':
        $controller = new choixRoueController();
        $controller->displayBut2();
        break;
    case 'but3':
        $controller = new choixRoueController();
        $controller->displayBut3();
        break;
    case 'ButGB':
        $controller = new choixBUT3Controller();
        $controller->displayBut3GB();
    // Ajoutez d'autres cas pour d'autres actions ici
    default:
        // Action par défaut ou page non trouvée
        // Vous pouvez rediriger vers la page d'accueil ou afficher une page d'erreur
        $controller = new HomeController();
        $controller->displayHome();
        exit;
        break;

/*
    $controller = new choixRoueController();
    $controller->displayRoue();

    $controller = new ScheduleController();
    $controller->handleRequest_Tab();
*/
}
?>
