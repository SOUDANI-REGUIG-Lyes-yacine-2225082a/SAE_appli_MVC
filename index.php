<?php
session_start();
require_once 'src/controller/ScheduleController.php';
require_once 'src/controller/HomeController.php';

$controller = new HomeController();
$controller->handleRequest();
/*
$controller = new ScheduleController();
$controller->handleRequest_Tab();
*/
?>

