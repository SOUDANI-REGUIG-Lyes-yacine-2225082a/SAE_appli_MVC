<?php

namespace src\controller;

use src\model\EventModel;
use Exception;
use src\view\ScheduleView;

class BaseScheduleController {
    private EventModel $eventModel;
    private ScheduleView $scheduleView;

    public function __construct() {
        $this->eventModel = new EventModel();
        $this->scheduleView = new ScheduleView();

    }


    /*$url = $this->eventModel->generateIcsUrl('BUT2gb', '2024-01-08', '2024-01-12');
    echo $url; // echo l'URL pour le vérifier
    avec cet ligne de commande :
        curl -o test.ics -L "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?
        projectId=8&resources=8382&calType=ical&firstDate=2023-01-01&lastDate=2023-01-07"*/

    public function displayGroupSchedule($groupeName, $firstDate, $lastDate)
    {
        // Obtenez la semaine actuelle ou une semaine spécifiée
        $weekDates = $this->getCurrentWeek();
        $currentWeek = $weekDates['firstDate']; // Utilisez firstDate pour la navigation

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'prevWeek':
                    $currentWeek = date('Y-m-d', strtotime("$currentWeek -1 week"));
                    break;
                case 'nextWeek':
                    $currentWeek = date('Y-m-d', strtotime("$currentWeek +1 week"));
                    break;
            }
        }

        // Recalculez firstDate et lastDate après la navigation
        $firstDate = date('Y-m-d', strtotime('Monday this week', strtotime($currentWeek)));
        $lastDate = date('Y-m-d', strtotime('Sunday this week', strtotime($currentWeek)));

        // Générer l'URL et récupérer les événements
        $events = $this->eventModel->retrieveIcs($groupeName, $firstDate, $lastDate);

        // Afficher l'emploi du temps
        $this->scheduleView->displaySchedule($events);
    }

    private function getCurrentWeek() {
        // Si une date spécifique est passée par GET (par exemple, lors de la navigation entre les semaines),
        // utilisez cette date pour trouver le lundi de la semaine correspondante.
        if (isset($_GET['week'])) {
            $week = $_GET['week'];
        } else {
            // Sinon, utilisez la date actuelle
            $week = date('Y-m-d');
        }

        // Calculer les dates de début et de fin de la semaine
        $firstDate = date('Y-m-d', strtotime('Monday this week', strtotime($week)));
        $lastDate = date('Y-m-d', strtotime('Sunday this week', strtotime($week)));

        return ['firstDate' => $firstDate, 'lastDate' => $lastDate];
    }




    public function handleWeekNavigation() {
        $groupName = $_GET['action'];
        // Déterminez la semaine actuelle basée sur une entrée de session ou la date actuelle
        $currentWeek = $_SESSION['currentWeek'] ?? date('Y-m-d');


        // Vérifiez si une action de navigation a été demandée
        if (isset($_GET['week'])) {
            if ($_GET['week'] === 'prevWeek') {
                $currentWeek = date('Y-m-d', strtotime("$currentWeek -1 week"));
            } elseif ($_GET['week'] === 'nextWeek') {
                $currentWeek = date('Y-m-d', strtotime("$currentWeek +1 week"));
            }
        }

        // Mettez à jour la semaine actuelle dans la session
        $_SESSION['currentWeek'] = $currentWeek;

        // Calculez firstDate et lastDate pour la semaine actuelle
        $firstDate = date('Y-m-d', strtotime('Monday this week', strtotime($currentWeek)));
        $lastDate = date('Y-m-d', strtotime('Sunday this week', strtotime($currentWeek)));

        // Appellez la méthode pour récupérer et afficher les événements

        $events = $this->eventModel->retrieveIcs($groupName, $firstDate, $lastDate);
        $this->scheduleView->displaySchedule($events);
    }

    public function updateWeekDates() {
        // Initialiser ou récupérer la semaine actuelle
        $currentWeek = $_SESSION['currentWeek'] ?? date('Y-m-d');

        // Ajuster la semaine en fonction de l'action utilisateur
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

        // Mettre à jour la session avec la nouvelle semaine
        $_SESSION['currentWeek'] = $currentWeek;

        // Rediriger vers la méthode d'affichage
        $this->displayGroupSchedule2($_SESSION['selectedGroupName']);
    }

    public function displayGroupSchedule2() {
        $groupName = $_GET['action'];
        // Utiliser les dates de la semaine actuelle
        $weekDates = $this->getCurrentWeekDates();

        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];

        // Récupérer et afficher l'emploi du temps
        $events = $this->eventModel->retrieveIcs($groupName, $firstDate, $lastDate);
        $this->scheduleView->displaySchedule($events);
    }

    private function getCurrentWeekDates() {
        $currentWeek = $_SESSION['currentWeek'] ?? date('Y-m-d');
        $firstDate = date('Y-m-d', strtotime('Monday this week', strtotime($currentWeek)));
        $lastDate = date('Y-m-d', strtotime('Sunday this week', strtotime($currentWeek)));

        return ['firstDate' => $firstDate, 'lastDate' => $lastDate];
    }








    //Cette fonction affiche l'emploie du temps du groupe de la current semaine mais y'a pas le changement de semaine
    public function displayGroupScheduleFonctionelSansWeekChangement($selectedGroups) {

        // Obtenez la semaine actuelle ou une semaine spécifiée
        $weekDates = $this->getCurrentWeek();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];

        try {
            // Récupère les événements pour les groupes sélectionnés et les dates spécifiées
            $events = $this->eventModel->retrieveIcs($selectedGroups, $firstDate, $lastDate);
            $this->scheduleView->displaySchedule($events);
        } catch (\Exception $e) { // Assurez-vous d'avoir le bon namespace pour Exception
            $this->scheduleView->displayError($e->getMessage());
        }
    }
}
