<?php
session_start();

require_once 'vendor/autoload.php';

use src\controller\HomeController;
use src\controller\ScheduleController;
use src\controller\choixRoueController;
use src\controller\choixBUT3Controller;
use src\controller\choixBUT1Controller;
use src\controller\choixBUT2Controller;


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

        /*BUT 1;; Annee = TOUT les GROUPES*/
    case 'BUT1Annee':
        $controller = new choixBUT1Controller();
        $controller->displayBut1Entier();
        break;
    case 'BUT1g1':
        $controller = new choixBUT1Controller();
        $controller->displayBut1G1();
        break;
    case 'BUT1g2':
        $controller = new choixBUT1Controller();
        $controller->displayBut1G2();
        break;
    case 'BUT1g3':
        $controller = new choixBUT1Controller();
        $controller->displayBut1G3();
        break;
    case 'BUT1g4':
        $controller = new choixBUT1Controller();
        $controller->displayBut1G4();
        break;

        /*BUT 2;; Annee = TOUT les GROUPES*/
    case 'BUT2A':
        $controller = new choixBUT2Controller();
        $controller->displayBut2Entier();
        break;
    case 'BUT2ga1':
        $controller = new choixBUT2Controller();
        $controller->displayBut2GA1();
        break;
    case 'BUT2ga2':
        $controller = new choixBUT2Controller();
        $controller->displayBut2GA2();
        break;
    case 'BUT2gb':
        $controller = new choixBUT2Controller();
        $controller->displayBut2GB();
        break;


    /*BUT 3;; Annee = TOUT les GROUPES*/
    case 'BUT3Annee':
        $controller = new choixBUT3Controller();
        $controller->displayBut3Entier();
        break;
    case 'BUT3ga1':
        $controller = new choixBUT3Controller();
        $controller->displayBut3GA1();
        break;
    case 'BUT3ga2':
        $controller = new choixBUT3Controller();
        $controller->displayBut3GA2();
        break;
    case 'But3GB':
        $controller = new choixBUT3Controller();
        $controller->displayBut3GB();
        break;

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
