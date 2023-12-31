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
            $_SESSION['currentWeek'] = date('Y-m-d'); // Définit la semaine actuelle à la date actuelle
        }

    }


    //CELUI LA C'EST LE BON QUI MARHCE SANS PARAMETRE DE GROUPE
    //fonction principale qui affiche les edts
    public function displayGroupSchedule2()
    {
        $groupName = $_GET['group'];

        $weekDates = $this->getCurrentWeek();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];

        // Récupérer et afficher l'emploi du temps
        $events = $this->eventModel->retrieveIcs($groupName, $firstDate, $lastDate);
        $this->scheduleView->displaySchedule($events, $groupName);
    }


    //fonction fonctionnelle lol, bref elle marche mais ya un parametre de groupe
    // je garde au cas où jen ai besoin pour les navigatins semaine
    public function displayGroupScheduleFonctionelSansWeekChangement($selectedGroups, $firstDate, $lastDate) {

        // Obtenez la semaine actuelle ou une semaine spécifiée
        /*$weekDates = $this->getCurrentWeek();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];
*/

        try {
            // Récupère les événements pour les groupes sélectionnés et les dates spécifiées
            $events = $this->eventModel->retrieveIcs($selectedGroups, $firstDate, $lastDate);
            $this->scheduleView->displaySchedule($events, $selectedGroups);
        } catch (\Exception $e) { 
            $this->scheduleView->displayError($e->getMessage());
        }
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

        return['firstDate' => $firstDate, 'lastDate' => $lastDate];
    }






    //Essai de fonction pour changer de semaine, marche pas, fonctionnalité à revoir plus tard
    //TODO: la faire fonctionné ptdrr
    public function handleWeekNavigation() {
        $currentWeek = $_SESSION['currentWeek'];


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
        $weekDates = $this->getCurrentWeek();
        $firstDate = $weekDates['firstDate'];
        $lastDate = $weekDates['lastDate'];
        $groupName = $_GET['group'];

        echo $firstDate;
        echo $lastDate;
        // Rediriger vers la méthode d'affichage de l'emploi du temps
        //$this->displayGroupScheduleFonctionelSansWeekChangement($groupName, $firstDate, $lastDate);
    }


    //même chose qu'au dessus, essai pour les navigations par semaine, marche pas
    //TODO : faire marchez l'une ou l'autre, je sais pas encore laquelle est la plus adapté
    public function navigateWeek($direction) {
        $currentWeek = $_SESSION['currentWeek'];

        if ($direction == 'prevWeek') {
            $currentWeek = date('Y-m-d', strtotime("$currentWeek -1 week"));
        } elseif ($direction == 'nextWeek') {
            $currentWeek = date('Y-m-d', strtotime("$currentWeek +1 week"));
        }

        $_SESSION['currentWeek'] = $currentWeek;

        // Rediriger vers la méthode d'affichage de l'emploi du temps
        $this->displayGroupSchedule2();
    }




    //le bloc en dessous est un test d'url que jai trouvé sur internet qui permet de "tester" un url

    /*$url = $this->eventModel->generateIcsUrl('BUT2gb', '2024-01-08', '2024-01-12');
    echo $url; // echo l'URL pour le vérifier, puis le copiez collé et posez sur un terminal
    avec cet ligne de commande :
        curl -o test.ics -L "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?
        projectId=8&resources=8382&calType=ical&firstDate=2023-01-01&lastDate=2023-01-07"*/





}
