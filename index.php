<?php
session_start();
require_once 'src/controller/ScheduleController.php';
require_once 'src/controller/HomeController.php';
require_once 'src/controller/AcceuilController.php';

$controller = new HomeController();
$controller->handleRequest();


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
?>

