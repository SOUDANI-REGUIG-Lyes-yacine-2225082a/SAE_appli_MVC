<?php


class EventModel
{
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

    public function retrieveMultipleIcs(array $adeLinks) {
        foreach ($adeLinks as $key => $link) {
            try {
                $this->recupIcs($link);
            } catch (Exception $e) {
                echo "Error for ADE code $key: " . $e->getMessage() . "\n";
            }
        }
        return $this->events; // Retourner les événements récupérés
    }
}
