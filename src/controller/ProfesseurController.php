<?php

namespace src\controller;

use src\model\ProfModel;
use src\view\ScheduleProfView;
use src\model\EventModel;
use src\controller\BaseScheduleController;


class ProfesseurController
{
    private ProfModel $model;
    private ScheduleProfView $view;

    private EventModel $eventModel;



    private BaseScheduleController $controller;
    private $profResourceIds = [];
    private string $filePath = 'listeProf.json';

    public function __construct()
    {
        $this->model = new ProfModel();
        $this->view = new ScheduleProfView();
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
            header("Location: index.php?group=ButEnseignant");
            exit();
        } else {
            echo "Erreur lors de l'ajout du professeur.";
        }
    }


    //ajouter professeur, fonction nommé 'index' je sais pas pourquoi trop tard pour changer
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

    public function getListeProfesseurs() {
        return array_keys($this->profResourceIds);
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


        // Récupérer et afficher l'emploi du temps
        $events = $this->model->retrieveIcs($profName, $firstDate, $lastDate);

        //$eventsByDayAndHour = $this->eventModel->getEventsStructuredByDayAndHour();

        error_log("EVENTS : " . print_r($events, true));
        $this->view->displaySchedule($events, $profName, $currentWeek);
    }
}