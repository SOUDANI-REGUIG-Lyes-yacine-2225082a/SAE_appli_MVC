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
    private $events = [];

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

    private function processEvent2($eventData) {
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
    }


    private function processEvent($eventData)
    {
        //error_log(print_r($eventData, true)); // Log pour le débogage / Resultat = données bien traités, pb pas ici
        $dayOfWeek = date('l', strtotime($eventData['DTSTART']));
        $startTimestamp = strtotime($eventData['DTSTART']);
        $endTimestamp = strtotime($eventData['DTEND']);
        $summary = $eventData['SUMMARY'] ?? 'No summary';
        $location = $eventData['LOCATION'] ?? 'No Location';
        $description = $eventData['DESCRIPTION'] ?? 'No Description';

        $startHour = (int)date('H', $startTimestamp);
        $endHour = (int)date('H', $endTimestamp);
        $endMinute = (int)date('i', $endTimestamp);

        if ($endMinute > 0) {
            ++$endHour;
        }

        $duration = ceil(($endTimestamp - $startTimestamp) / 3600);

        if (!isset($this->events[$dayOfWeek])) {
            $this->events[$dayOfWeek] = [];
        }


        $eventEntry = [
            'start' => date('H:i', $startTimestamp),
            'end' => date('H:i', $endTimestamp),
            'summary' => $summary,
            'location' => $location,
            'description' => $this->prepareData($description),
            'rowspan' => $duration
        ];
        //error_log("Event processed: " . print_r($eventEntry, true));
        $this->events[$dayOfWeek][$startHour][] = $eventEntry;

        /*
        for ($hour = $startHour + 1; $hour < $endHour; $hour++) {
            $this->events[$dayOfWeek][$hour][] = 'covered';
        }*/
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

    public function getEventsStructuredByDayAndHour() {
        $structuredEvents = [];

        // Parcourir tous les événements et les structurer
        foreach ($this->events as $event) {
            $startTimestamp = strtotime($event['DTSTART']);
            $endTimestamp = strtotime($event['DTEND']);




            //error_log("Start Timestamp: $startTimestamp\n");
            //error_log("End Timestamp: $endTimestamp\n");


            $startHour = (int)date('H', $startTimestamp);
            $endHour = (int)date('H', $endTimestamp);

            //error_log("Start Hour: $startHour\n");
            //error_log("End Hour: $endHour\n");




            $dayOfWeek = date('l', strtotime($event['start'])); // 'Monday', 'Tuesday', ...
            //$startHour = (int)date('H', strtotime($event['start']));
            //$endHour = (int)date('H', strtotime($event['end']));
            $eventDurationHours = $endHour - $startHour;



            // Assurez-vous que la clé pour le jour de la semaine existe
            if (!array_key_exists($dayOfWeek, $structuredEvents)) {
                $structuredEvents[$dayOfWeek] = [];
            }

            // Assurez-vous que la clé pour l'heure de début existe
            if (!array_key_exists($startHour, $structuredEvents[$dayOfWeek])) {
                $structuredEvents[$dayOfWeek][$startHour] = [];
            }

            // Ajouter l'événement avec la durée et les heures de début et de fin
            $structuredEvents[$dayOfWeek][$startHour][] = [
                'start' => $startHour,
                'end' => $endHour,
                'duration' => $eventDurationHours,
                'details' => $event, // contient tous les détails de l'événement
            ];
        }
        //error_log("Events by day and hour: " . print_r($this->events, true));
        return $structuredEvents;
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


}


