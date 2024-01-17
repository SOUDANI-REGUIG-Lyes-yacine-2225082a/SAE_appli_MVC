<?php

namespace src\controller;

use src\model\ProfModel;
use src\view\ScheduleView;
use src\model\EventModel;
use src\controller\BaseScheduleController;


class ProfesseurController
{
    private ProfModel $model;
    private ScheduleView $view;

    private EventModel $eventModel;

    private BaseScheduleController $controller;

    public function __construct()
    {
        $this->model = new ProfModel();
        $this->view = new ScheduleView();
        $this->eventModel = new EventModel();
        $this->controller = new BaseScheduleController();
    }

    public function ajouter()
    {
        $nom = $_POST['profName'] ?? '';
        $id = $_POST['profId'] ?? '';

        if ($this->model->ajouterProfesseur($nom, $id)) {
            header("Location: index.php?controller=professeur&action=index");
            exit();
        } else {
            echo "Erreur lors de l'ajout du professeur.";
        }
    }

    public function index()
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['profName'] ?? '';
            $id = $_POST['profId'] ?? '';

            if (empty($nom) || empty($id)) {
                $message = "Veuillez remplir tous les champs.";
            } else {
                if ($this->model->ajouterProfesseur($nom, $id)) {
                    $message = "Professeur ajouté avec succès.";
                } else {
                    $message = "Le professeur existe déjà ou une erreur s'est produite.";
                }
            }
        }

        $professeurs = $this->model->getListeProfesseurs();
        $profResourceIds = $this->model->profResourceIds;
        include 'src/view/ButEnseignant.php';

    }

    public function displayEnseigant()
    {
        include 'src/view/EdtProf.php';
        $this->controller->displayGroupSchedule2();
    }
}