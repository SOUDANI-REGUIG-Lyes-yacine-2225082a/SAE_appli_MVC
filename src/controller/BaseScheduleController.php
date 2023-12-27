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

    public function displayGroupSchedule(array $groupKeys) {
        try {
            $events = $this->eventModel->retrieveMultipleIcs($groupKeys);
            $this->scheduleView->displaySchedule($events);
        } catch (Exception $e) {
            $this->scheduleView->displayError($e->getMessage());
        }
    }
}
