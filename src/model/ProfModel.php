<?php

namespace src\model;
use Exception;

class ProfModel
{

    private $baseUrl = "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp";
    private $projectId = 8; // projectId fixe
    public $profResourceIds = [
        'Alain Casali' => [133241,144299,72019],

    ];
    private $events = [];

    public function generateIcsUrl($identifier, $firstDate, $lastDate, $isProf = false) {
        $resourceIds = $isProf ? $this->profResourceIds : $this->groupResourceIds;

        if (!isset($resourceIds[$identifier])) {
            throw new \Exception("Resource ID not found for: " . $identifier);
        }

        $resourceId = $resourceIds[$identifier];

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
    public function getListeProfesseurs() {
        return array_keys($this->profResourceIds);
    }
    public function ajouterProfesseur($nom, $id) {
        if (array_key_exists($nom, $this->profResourceIds)) {
            return false;
        }

        $this->profResourceIds[$nom] = (int)$id;

        return true;

}

}


