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
        if (!isset($_SESSION['currentWeek'])) {
            $_SESSION['currentWeek'] = date('Y-m-d'); // Assurez-vous que cela définit correctement la date actuelle
        }

    }


    //CELUI LA C'EST LE BON QUI MARHCE SANS PARAMETRE DE GROUPE
    //fonction principale qui affiche les edts
    public function displayGroupSchedule2() {
        // Récupérer le nom du groupe
        $groupName = $_GET['group'];

        // Si aucune navigation n'est demandée, réinitialiser la semaine actuelle
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
        $weekDates = $this->getCurrentWeekDates();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];

        echo $firstDate;
        echo " / ";
        echo $lastDate;
        // Récupérer et afficher l'emploi du temps
        $events = $this->eventModel->retrieveIcs($groupName, $firstDate, $lastDate);
        $this->scheduleView->displaySchedule($events, $groupName);
    }


    private function getCurrentWeekDates() {
        $currentWeek = $_SESSION['currentWeek'] ?? date('Y-m-d');
        $firstDate = date('Y-m-d', strtotime('Monday this week', strtotime($currentWeek)));
        $lastDate = date('Y-m-d', strtotime('Sunday this week', strtotime($currentWeek)));

        return ['firstDate' => $firstDate, 'lastDate' => $lastDate];
    }








    //le bloc en dessous est un test d'url que jai trouvé sur internet qui permet de "tester" un url

    /*$url = $this->eventModel->generateIcsUrl('BUT2gb', '2024-01-08', '2024-01-12');
    echo $url; // echo l'URL pour le vérifier, puis le copiez collé et posez sur un terminal
    avec cet ligne de commande :
        curl -o test.ics -L "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?
        projectId=8&resources=8382&calType=ical&firstDate=2023-01-01&lastDate=2023-01-07"*/
/*
    public function displayGroupScheduleFonctionelSansWeekChangement($selectedGroups) {

        // Obtenez la semaine actuelle ou une semaine spécifiée
        $weekDates = $this->getCurrentWeek();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];


        try {
            // Récupère les événements pour les groupes sélectionnés et les dates spécifiées
            $events = $this->eventModel->retrieveIcs($selectedGroups, $firstDate, $lastDate);
            $this->scheduleView->displaySchedule($events, $selectedGroups);
        } catch (\Exception $e) {
            $this->scheduleView->displayError($e->getMessage());
        }
    }
*/



}
