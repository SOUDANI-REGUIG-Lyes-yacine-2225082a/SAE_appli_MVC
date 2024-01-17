<?php

namespace src\controller;
use PHPUnit\Framework\TestCase;
use src\controller\ProfesseurController;
class ProfesseurControllerTest
{
    public function testAjouter() {
        $_POST['profName'] = 'NomProf';
        $_POST['profId'] = '1234';

        $controller = new ProfesseurController();
        $controller->ajouter();

    }
    public function testSupprimer() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['profName'] = 'NomProf';

        $controller = new ProfesseurController();
        $controller->supprimer();

    }
    public function testIndex() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['profName'] = 'NomProf';
        $_POST['profId'] = '1234';

        $controller = new ProfesseurController();
        $controller->index();

    }
    public function testDisplayEnseignant() {
        $_GET['profName'] = 'NomProf';

        $controller = new ProfesseurController();
        $controller->displayEnseignant();

    }

}