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

    public function downloadIcs($groupName, $firstDate, $lastDate) {
        try {
            $url = $this->eventModel->generateIcsUrl($groupName, $firstDate, $lastDate);
            // Code pour initier le téléchargement du fichier ICS
        } catch (\Exception $e) {
            echo 'Erreur BaseScheduleController fct downloadIcs';
        }
    }



    public function displayGroupSchedule($selectedGroup, $firstDate, $lastDate) {
        try {
            // Récupère les événements en utilisant les nouveaux paramètres de dates
            $events = $this->eventModel->retrieveMultipleIcs($selectedGroup, $firstDate, $lastDate);
            $this->scheduleView->displaySchedule($events);
        } catch (Exception $e) {
            $this->scheduleView->displayError($e->getMessage());
        }
    }




    /*
     * A modif pour que le parametre soit un code ade ou qql chose comme pour le download automatique
    public function afficherEdt($selectedGroups) {
        try {
            $events = $this->eventModel->retrieveMultipleIcs($selectedGroups);
            $this->scheduleView->displaySchedule($events);
        } catch (\Exception $e) {
            $this->scheduleView->displayError($e->getMessage());
        }
    }
    */
}
