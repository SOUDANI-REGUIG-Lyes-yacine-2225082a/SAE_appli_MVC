<?php
session_start();

require_once 'vendor/autoload.php';
require_once 'Router.php';

use src\controller\HomeController;
use src\controller\choixRoueController;
use src\controller\choixBUT3Controller;
use src\controller\choixBUT1Controller;
use src\controller\choixBUT2Controller;

    $router = new Router();
// Ajoutez les routes ici
    $router->addRoute('home', HomeController::class, 'displayHome');
    $router->addRoute('roueDeChoix', choixRoueController::class, 'displayRoue');
    $router->addRoute('but1', choixRoueController::class, 'displayBut1');
    $router->addRoute('but2', choixRoueController::class, 'displayBut2');
    $router->addRoute('but3', choixRoueController::class, 'displayBut3');

    $router->addRoute('BUT1Annee', choixBUT1Controller::class, 'displayBut1Entier');
    $router->addRoute('BUT1g1', choixBUT1Controller::class, 'displayBut1G1');
    $router->addRoute('BUT1g2', choixBUT1Controller::class, 'displayBut1G2');
    $router->addRoute('BUT1g3', choixBUT1Controller::class, 'displayBut1G3');
    $router->addRoute('BUT1g4', choixBUT1Controller::class, 'displayBut1G4');

    $router->addRoute('BUT2Annee', choixBUT2Controller::class, 'displayBut2Full');
    $router->addRoute('BUT2ga1', choixBUT2Controller::class, 'displayBut2GA1');
    $router->addRoute('BUT2ga2', choixBUT2Controller::class, 'displayBut2GA2');
    $router->addRoute('BUT2gb', choixBUT2Controller::class, 'displayBut2GB');

    $router->addRoute('BUT3Annee', choixBUT3Controller::class, 'displayBut3Entier');
    $router->addRoute('BUT3ga1', choixBUT3Controller::class, 'displayBut3GA1');
    $router->addRoute('BUT3ga2', choixBUT3Controller::class, 'displayBut3GA2');
    $router->addRoute('BUT3gb', choixBUT3Controller::class, 'displayBut3GB');

    $router->addRoute('salles', choixRoueController::class, 'displaySalles');

    $router->addRoute('ButEnseignant', choixRoueController::class, 'displayButEnseignant');

    $group = $_GET['group'] ?? 'home'; // 'home' est le group par défaut
    $router->dispatch($group);
?>
