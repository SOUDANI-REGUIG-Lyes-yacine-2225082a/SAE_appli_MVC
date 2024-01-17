<?php

namespace src\model;
use Exception;
use src\controller\ProfesseurController;
use src\model\EventModel;

class ProfModel
{

    private $baseUrl = "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp";
    private $projectId = 8; // projectId fixe

    private $events = [];
    private ProfesseurController $controller;
    private EventModel $model;

    private $profResourceIds = []; // Ajoutez ceci pour stocker les IDs des professeurs

    public function __construct() {
        $this->loadProfessors(); // Chargez les professeurs à partir du fichier JSON
        $this->model = new EventModel();

    }

    private function loadProfessors() {
        $filePath = 'listeProf.json'; // Chemin vers le fichier JSON

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $this->profResourceIds = json_decode($jsonData, true);
        } else {
            $this->profResourceIds = [];
        }
    }

    public function generateIcsUrl($groupName, $firstDate, $lastDate) {
        // Vérifiez si le nom du groupe correspond à un professeur
        if (isset($this->profResourceIds[$groupName])) {
            $resourceId = $this->profResourceIds[$groupName];
        } elseif (isset($this->groupResourceIds[$groupName])) {
            $resourceId = $this->groupResourceIds[$groupName];
        } else {
            throw new \Exception("Resource ID not found for: " . $groupName);
        }

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
        //error_log("EVENTS : " . print_r($value, true));
        return $value;
    }

    public function recupIcs($url) {
        $url = html_entity_decode($url);
        $icsContent = file_get_contents($url);
        //error_log("EVENTS" . print_r("$icsContent", true));
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

    public function processEvent($eventData)
    {
        //error_log(print_r($eventData, true)); // Log pour le débogage / Resultat = données bien traités, pb pas ici
        $dayOfWeek = date('l', strtotime($eventData['DTSTART']));
        $startTimestamp = strtotime($eventData['DTSTART']);
        $endTimestamp = strtotime($eventData['DTEND']);


        $summary = $eventData['SUMMARY'] ?? 'No summary';
        $location = $eventData['LOCATION'] ?? 'No Location';
        $description = $eventData['DESCRIPTION'] ?? 'No Description';


        $endHour = (int)date('H', $endTimestamp);
        $endMinute = (int)date('i', $endTimestamp);


        $startHour = (int)date('H', $startTimestamp);
        $formattedHour = str_pad($startHour, 2, '0', STR_PAD_LEFT);


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
        $this->events[$dayOfWeek][$formattedHour][] = $eventEntry;

        /*
        for ($hour = $startHour + 1; $hour < $endHour; $hour++) {
            $this->events[$dayOfWeek][$hour][] = 'covered';
        }*/
    }

    public function retrieveIcs($resourceId, $firstDate, $lastDate) {
        $this->events = [];
        try {
            $url = $this->generateIcsUrl($resourceId, $firstDate, $lastDate);
            //error_log("URL : " . $url);
            if (!is_string($url)) {
                throw new Exception("L'URL générée n'est pas une chaîne valide.");
            }
            // Récupérer le contenu ICS à partir de l'URL
            $this->recupIcs($url);
        } catch (Exception $e) {
            echo "Erreur pour le resourceId $resourceId: " . $e->getMessage() . "\n";
        }
        error_log("RETIEVEICS : " . print_r($this->events, true));
        return $this->events;
    }
    public function getListeProfesseurs() {
        return array_keys($this->profResourceIds);
    }
    public function ajouterProfesseur($nom, $id) {
        if (array_key_exists($nom, $this->profResourceIds)) {
            return false; // Le professeur existe déjà
        }
        $this->profResourceIds[$nom] = $id;
        $this->controller->saveProfessors(); // Enregistrer dans le fichier JSON
        return true;
    }

}


