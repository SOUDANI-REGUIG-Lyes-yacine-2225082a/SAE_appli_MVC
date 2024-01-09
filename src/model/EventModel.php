<?php

namespace src\model;
use Exception;

class EventModel
{

    private $baseUrl = "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp";
    private $projectId = 8; // projectId fixe
    private $groupResourceIds = [
        'BUT1g1'      => 8382,
        'BUT1g2'    => 8380,
        'BUT1g3'    => 8383,
        'BUT1g4'    => 8381,
        'BUT1Annee' => 8379,

        'BUT2ga1'   => 8396,
        'BUT2ga2'   => 8397,
        'BUT2gb'    => 8398,
        'BUT2Annee' => 45843,

        'BUT3ga1'   => 42523,
        'BUT3ga2'   => 42524,
        'BUT3gb'    => 45225,
        'BUT3Annee' => 8408
    ];
    private $allRooms = [
        "TD I-206", "TD I-205", "TD I-207", "TD I-208", "TD I-209", "TD I-212", "TD I-211", "TD I-214",
        "TP I-102", "TD I-104", "TD I-107", "TP I-106", "TD I-109", "TD I-111", "TD I-110",
        "TP I-002", "TP I-004", "TP I-009", "TP I-010"
    ];
    private $events = [];
    private $occupiedRooms = [];

    public function generateIcsUrl($groupName, $firstDate, $lastDate) {
        if (!isset($this->groupResourceIds[$groupName])) {
            throw new \Exception("ResourceId non trouvé pour le groupe : " . $groupName);
        }

        $resourceId = $this->groupResourceIds[$groupName];

        $queryParams = http_build_query([
            'projectId' => $this->projectId,
            'resources' => $resourceId,
            'calType' => 'ical',
            'firstDate' => $firstDate,
            'lastDate' => $lastDate
        ]);
        return $this->baseUrl . '?' . $queryParams;
    }


    public function prepareData($value) {
        if (is_string($value)) {
            $value = preg_replace('/\b\d{13,}\b/', '', $value);
            /*a chaque '\n' rencontré il est enlevé du texte et remplacé par une EOL(End of line pour les ignorants)*/
            $value = str_replace('\n', PHP_EOL, $value);
            $value = preg_replace('/\(Modifié le:.*?\)/', '', $value);
            $value = preg_replace('/\(Modif.*/', '', $value);

            /*Supprime les antislashs*/
            $value = stripslashes($value);
        } elseif (is_array($value)) {
            return array_map([$this, 'prepareData'], $value);
        }
        return $value;
    }

    public function recupIcs($url) {
        $url = html_entity_decode($url);
        $icsContent = file_get_contents($url);
        if ($icsContent === false) {
            throw new Exception("Unable to retrieve ICS content.");
        }

        $lines = explode("\n", $icsContent);
        foreach ($lines as $line) {
            if (strpos($line, 'BEGIN:VEVENT') !== false) {
                $event = [];
            } elseif (strpos($line, 'END:VEVENT') !== false) {
                $this->processEvent($event);
            } elseif (isset($event)) {
                if (strpos($line, ':') !== false) {
                    list($key, $value) = explode(':', $line, 2);
                    $event[$key] = $this->prepareData($value); // Appel à prepareData pour nettoyer la valeur
                }
            }
        }
    }

    private function processEvent($eventData) {
        $dayOfWeek = date('l', strtotime($eventData['DTSTART'])); // Convertit en jour de la semaine
        $startTime = date('H:i', strtotime($eventData['DTSTART'])); // Heure de début au format HH:mm
        $endTime = date('H:i', strtotime($eventData['DTEND'])); // Heure de fin au format HH:mm
        $summary = $eventData['SUMMARY'];
        $location = $eventData['LOCATION'];
        $description = $eventData['DESCRIPTION'];

        if (!isset($this->events[$dayOfWeek])) {
            $this->events[$dayOfWeek] = [];
        }

        $this->events[$dayOfWeek][] = [
            'start' => $startTime,
            'end' => $endTime,
            'summary' => $summary,
            'location' => $location,
            'description' => $this->prepareData($description)
        ];
        $this->occupiedRooms[$dayOfWeek][$location][] = ['start' => $startTime, 'end' => $endTime];
    }

    public function retrieveIcs($resourceId, $firstDate, $lastDate) {
        $this->events = [];

        try {

            $url = $this->generateIcsUrl($resourceId, $firstDate, $lastDate);

            if (!is_string($url)) {
                throw new Exception("L'URL générée n'est pas une chaîne valide.");
            }

            // Récupérer le contenu ICS à partir de l'URL
            $this->recupIcs($url);
        } catch (Exception $e) {
            echo "Erreur pour le resourceId $resourceId: " . $e->getMessage() . "\n";
        }

        return $this->events;
    }




    /*
        public function retrieveMultipleIcs($selectedGroups, $firstDate, $lastDate) {
            $this->events = [];

            foreach ($selectedGroups as $action) {
                if(isset($this->groupResourceIds[$action])){
                    try {
                        $url = $this->generateIcsUrl($action, $firstDate, $lastDate);
                        $this->recupIcs($url);
                    } catch (Exception $e) {
                        echo "Erreur pour le groupe $action: " . $e->getMessage() . "\n";
                    }
                }
            }

            return $this->events;
        }*/

    public function getAvailableRooms() {
        $currentDay = date('l');
        $currentTime = date('H:i');

        $availableRooms = $this->allRooms;

        if (isset($this->occupiedRooms[$currentDay])) {
            foreach ($this->occupiedRooms[$currentDay] as $room => $times) {
                foreach ($times as $time) {
                    if ($currentTime >= $time['start'] && $currentTime <= $time['end']) {
                        if (($key = array_search($room, $availableRooms)) !== false) {
                            unset($availableRooms[$key]);
                        }
                    }
                }
            }
        }

        return array_values($availableRooms);
    }

}


