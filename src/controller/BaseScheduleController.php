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

    public function displayGroupSchedule($selectedGroups, $firstDate, $lastDate) {
        /*$url = $this->eventModel->generateIcsUrl('BUT2gb', '2024-01-08', '2024-01-12');
        echo $url; // echo l'URL pour le vérifier*/
        try {

            // Récupère les événements pour les groupes sélectionnés et les dates spécifiées
            $events = $this->eventModel->retrieveIcs($selectedGroups, $firstDate, $lastDate);
            $this->scheduleView->displaySchedule($events);
        } catch (\Exception $e) { // Assurez-vous d'avoir le bon namespace pour Exception
            $this->scheduleView->displayError($e->getMessage());
        }
    }

}
