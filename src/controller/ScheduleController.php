<?php

namespace src\controller;

use src\model\EventModel;
use src\view\ScheduleView;

/**
 * Classe ScheduleController
 *
 * Cette classe gère l'affichage de l'emploi du temps en récupérant des données d'événements
 * à partir de plusieurs liens ADE pour différentes classes/groupes.
 */
class ScheduleController {
    /**
     * @var EventModel Le modèle d'événement pour récupérer les données.
     */
    private $eventModel;
    /**
     * @var ScheduleView La vue d'affichage de l'emploi du temps.
     */

    private $scheduleView;
    /**
     * Constructeur de la classe ScheduleController.
     * Initialise les instances de EventModel et ScheduleView.
     */
    public function __construct() {
        $this->eventModel = new EventModel();
        $this->scheduleView = new ScheduleView();
    }

    // ScheduleController.php
    public function showAvailableRooms() {
        try {
            $weekDates = $this->getCurrentWeekDates();
            $firstDate = $weekDates['firstDate'];
            $lastDate = $weekDates['lastDate'];
            $this->eventModel->retrieveIcsSalles($firstDate, $lastDate);
            $availableRooms = $this->eventModel->getAvailableRooms();
            include "src/view/ButSalles.php";

        } catch (Exception $e) {
            $this->scheduleView->displayError($e->getMessage());
        }
    }

    private function getCurrentWeekDates() {
        $currentWeek = $_SESSION['currentWeek'] ?? date('Y-m-d');
        $firstDate = date('Y-m-d', strtotime('Monday this week', strtotime($currentWeek)));
        $lastDate = date('Y-m-d', strtotime('Sunday this week', strtotime($currentWeek)));

        return ['firstDate' => $firstDate, 'lastDate' => $lastDate];
    }


}
