<?php
session_start();
require_once 'src/controller/ScheduleController.php';
require_once 'src/controller/HomeController.php';
require_once 'src/controller/AcceuilController.php';


$action = $_GET['action'] ?? 'default';

switch ($action) {
    case 'home':
        $controller = new HomeController();
        $controller->displayHome();
        break;
    case 'roueDeChoix':
        $controller = new AcceuilController();
        $controller->displayRoue();
        break;
    // Ajoutez d'autres cas pour d'autres actions ici
    default:
        // Action par défaut ou page non trouvée
        // Vous pouvez rediriger vers la page d'accueil ou afficher une page d'erreur
        $controller = new HomeController();
        $controller->displayHome();
        exit;
        break;



    /*
     * affichage 2eme page avec roue pour choix des groupes etc ...
    $controller = new AcceuilController();
    $controller->displayHome();
    */
    /*
     * affichage tableau emploie du temps
    $controller = new ScheduleController();
    $controller->handleRequest_Tab();
    */
}
?>
