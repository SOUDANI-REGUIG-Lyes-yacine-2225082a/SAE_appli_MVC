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
    public function afficherEdt($selectedGroups) {
        try {
            $events = $this->eventModel->retrieveMultipleIcs($selectedGroups);
            $this->scheduleView->displaySchedule($events);
        } catch (\Exception $e) {
            $this->scheduleView->displayError($e->getMessage());
        }
    }

}
