<?php

require_once 'src/view/ScheduleView.php';
require_once 'src/model/EventModel.php';

class ScheduleController {

    private $eventModel;
    private $scheduleView;

    public function __construct() {
        $this->eventModel = new EventModel();
        $this->scheduleView = new ScheduleView();
    }

    public function handleRequest_Tab() {
        $adeLinks = [
            //1AG1
            1 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8382&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //1AG2
            2 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8380&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //1AG3
            3 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8383&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //1AG4
            4 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8381&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //1A
            5 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8379&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //2AGA1
            6 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8396&calType=ical&firstDate=2024-01-15&lastDate=2024-01-19",
            //2AGA2
            7 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8397&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //2AGB
            8 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8398&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //2A
            9 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=45843&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //3AGA1
            10 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42523&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //3AGA2
            11 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42524&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //3AGB
            12 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42525&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
            //3A
            13 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8408&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22"
        ];

        try {
            // Récupérer les données des événements
            $events = $this->eventModel->retrieveMultipleIcs($adeLinks);

            // Afficher l'emploi du temps
            $this->scheduleView->displaySchedule($events);
        } catch (Exception $e) {
            // Gérer les exceptions, par exemple en affichant un message d'erreur
            $this->scheduleView->displayError($e->getMessage());
        }
    }
}
