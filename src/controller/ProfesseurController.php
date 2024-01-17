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
    private $profResourceIds;

    public function __construct()
    {
        $this->model = new ProfModel();
        $this->view = new ScheduleView();
        $this->eventModel = new EventModel();
        $this->controller = new BaseScheduleController();
        $this->loadProfessors();
    }

    public function ajouter() {
        $nom = $_POST['profName'] ?? '';
        $id = $_POST['profId'] ?? '';

        if (!empty($nom) && !empty($id)) {
            $this->profResourceIds[$nom] = $id;
            $this->saveProfessors();
            header("Location: index.php?controller=professeur&action=index");
            exit();
        } else {
            echo "Erreur lors de l'ajout du professeur.";
        }
    }

    public function index() {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['profName'] ?? '';
            $id = $_POST['profId'] ?? '';

            if (empty($nom) || empty($id)) {
                $message = "Veuillez remplir tous les champs.";
            } else {
                // Vérifiez si le professeur existe déjà
                if (array_key_exists($nom, $this->profResourceIds)) {
                    $message = "Le professeur existe déjà.";
                } else {
                    // Ajouter le nouveau professeur et sauvegarder les modifications
                    $this->profResourceIds[$nom] = $id;
                    $this->saveProfessors();
                    $message = "Professeur ajouté avec succès.";
                }
            }
        }

        // Charger la liste des professeurs à partir du fichier JSON
        $this->loadProfessors();
        $professeurs = $this->profResourceIds; // Utilisez cette variable dans votre vue
        include 'src/view/ButEnseignant.php';
    }

    public function loadProfessors() {
        $filePath = 'listeProf.json';

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $this->profResourceIds = json_decode($jsonData, true);
        } else {
            $this->profResourceIds = [];
        }
    }

    public function saveProfessors() {
        $filePath = 'listeProf.json';
        $jsonData = json_encode($this->profResourceIds);
        file_put_contents($filePath, $jsonData);
    }


    public function displayEnseignant()
    {
        include 'src/view/EdtProf.php';
        $profName = $_GET['profName'] ?? '';

        if (!$profName) {
            echo "Nom du professeur non spécifié";
            return;
        }
        if (!isset($_GET['week'])) {
            $_SESSION['currentWeek'] = date('Y-m-d');
        }

        // màj de la semaine basé sur l'action de l'utilisateur
        $currentWeek = $_SESSION['currentWeek'] ?? date('Y-m-d');

        if (isset($_GET['week'])) {
            switch ($_GET['week']) {
                case 'prevWeek':
                    $currentWeek = date('Y-m-d', strtotime("$currentWeek -1 week"));
                    break;
                case 'nextWeek':
                    $currentWeek = date('Y-m-d', strtotime("$currentWeek +1 week"));
                    break;
            }
        }

        $_SESSION['currentWeek'] = $currentWeek;

        // Calculer debutSemaine et dinSemaine
        $weekDates = $this->controller->getCurrentWeekDates();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];

        echo $firstDate;
        echo " / ";
        echo $lastDate;
        // Récupérer et afficher l'emploi du temps
        $events = $this->eventModel->retrieveIcs($profName, $firstDate, $lastDate);

        //$eventsByDayAndHour = $this->eventModel->getEventsStructuredByDayAndHour();

        //error_log("EVENTS : " . print_r($events, true));
        $this->view->displaySchedule($events, $profName, $currentWeek);

    }
}