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
        'BUT3Annee' => 8408,

        'TousLesGroupesPourLesSalles' => 6432
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
        $this->occupiedRooms[$dayOfWeek][$location][] = ['start' => $startTime, 'end' => $endTime];
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
    public function retrieveIcsSalles($firstDate, $lastDate) {
        $this->events = [];

        try {

            $url = $this->generateIcsUrl('TousLesGroupesPourLesSalles', $firstDate, $lastDate);

            if (!is_string($url)) {
                throw new Exception("L'URL générée n'est pas une chaîne valide.");
            }

            // Récupérer le contenu ICS à partir de l'URL
            $this->recupIcsSalles($url);
        } catch (Exception $e) {
            echo "Erreur pour le resourceId : " . $e->getMessage() . "\n";
        }

        return $this->events;
    }

    private function processEventSalles($eventData) {
        $dayOfWeek = date('l', strtotime($eventData['DTSTART'])); // Convertit en jour de la semaine
        $startTime = date('H:i', strtotime($eventData['DTSTART'])); // Heure de début au format HH:mm
        $endTime = date('H:i', strtotime($eventData['DTEND'])); // Heure de fin au format HH:mm
        $location = $eventData['LOCATION'];

        if (!isset($this->events[$dayOfWeek])) {
            $this->events[$dayOfWeek] = [];
        }

        $this->events[$dayOfWeek][] = [
            'location' => $location,
        ];
        $locations = explode(',', $eventData['LOCATION']);
        foreach ($locations as $location) {
            $location = trim($location); // Enlève les espaces superflus
            if (!empty($location)) {
                $this->occupiedRooms[$dayOfWeek][$location][] = [
                    'start' => $startTime,
                    'end' => $endTime
                ];
            }
        }


        $this->occupiedRooms[$dayOfWeek][$location][] =
            ['start' => $startTime, 'end' => $endTime];
    }
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


    public function recupIcsSalles($url) {
        $url = html_entity_decode($url);

        // Initialiser une session cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Pour retourner le contenu téléchargé
        curl_setopt($ch, CURLOPT_HEADER, false); // Pour ne pas inclure l'en-tête dans le contenu
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Pour suivre les redirections
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // Nombre maximum de redirections à suivre
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout de la requête
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Désactiver la vérification SSL si nécessaire

        // Exécuter la requête cURL
        $icsContent = curl_exec($ch);
        if (curl_errno($ch)) {
            // Gérer l'erreur cURL
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        // Traiter le contenu ICS
        $lines = explode("\n", $icsContent);
        foreach ($lines as $line) {
            if (strpos($line, 'BEGIN:VEVENT') !== false) {
                $event = [];
            } elseif (strpos($line, 'END:VEVENT') !== false) {
                $this->processEventSalles($event);
            } elseif (isset($event)) {
                if (strpos($line, ':') !== false) {
                    list($key, $value) = explode(':', $line, 2);
                    $event[$key] = $this->prepareData($value); // Appel à prepareData pour nettoyer la valeur
                }
            }
        }
    }


}


