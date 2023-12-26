<?php

namespace src\model;

class EventModel
{
    private $adeLinks = [
    //1AG1
    1 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8382&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1AG2
    2 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8380&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1AG3
    3 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8383&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //1AG4
    4 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8381&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //But1
    5 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8379&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",

        //2AGA1
    6 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8396&calType=ical&firstDate=2024-01-15&lastDate=2024-01-19",
    //2AGA2
    7 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8397&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //2AGB
    8 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8398&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
        //But2
    9 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=45843&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",

        //3AGA1
    10 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42523&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //3AGA2
    11 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42524&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //3AGB
    12 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=42525&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22",
    //But3
    13 => "https://ade-web-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=8408&calType=ical&firstDate=2023-12-18&lastDate=2023-12-22"
    ];
    private $events = [];


    public function prepareData($value) {
        if (is_string($value)) {
            $value = preg_replace('/\b\d{13,}\b/', '', $value);
            $value = str_replace('\n', PHP_EOL, $value);
            $value = stripslashes($value);
        } elseif (is_array($value)) {
            return array_map([$this, 'prepareData'], $value);
        }
        return $value;
    }

    public function recupIcs($url) {
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

        if (!isset($this->events[$dayOfWeek])) {
            $this->events[$dayOfWeek] = [];
        }

        $this->events[$dayOfWeek][] = [
            'start' => $startTime,
            'end' => $endTime,
            'summary' => $summary
        ];
    }

    public function retrieveMultipleIcsAvant(array $adeLinks) {
        foreach ($adeLinks as $key => $link) {
            try {
                $this->recupIcs($link);
            } catch (Exception $e) {
                echo "Error for ADE code $key: " . $e->getMessage() . "\n";
            }
        }
        return $this->events; // Retourner les événements récupérés
    }

    public function retrieveMultipleIcs(array $selectedGroups) {
        $this->events = [];

        foreach ($selectedGroups as $groupKey) {
            if (isset($this->adeLinks[$groupKey])) {
                try {
                    $this->recupIcs($this->adeLinks[$groupKey]);
                } catch (Exception $e) {
                    echo "Error" . $e->getMessage() . "\n";
                }
            }
        }

        return $this->events;

    }
}
