<?php

namespace src\view;

class ScheduleView {

    public function displaySchedule($eventsByDayAndHour, $groupName, $currentWeekDate)
    {
        ob_start();
        // Ajoutez ici le formulaire de navigation
        echo '<form method="GET" action="/index.php">';
        echo '<input type="hidden" name="action" value="navigateWeek">';
        if ($groupName == 'navigateWeek') {
            echo 'navigateWeek ne peut pas etre un group';
        }
        echo '<input type="hidden" name="group" value="' . htmlspecialchars($groupName) . '">';
        echo '<button type="submit" name="week" value="prevWeek">Semaine précédente</button>';
        echo '<button type="submit" name="week" value="nextWeek">Semaine suivante</button>';

        echo '</form>';

        echo '<div class="schedule-container">';
        echo '<table class="schedule-table">';


        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $weekDates = $this->getWeekDates($currentWeekDate, $daysOfWeek);
        echo '<tr>';
        echo '<th>Heure</th>'; // Colonne pour les heures si nécessaire
        foreach ($weekDates as $day => $date) {
            echo '<th>' . $day . ' ' . $date . '</th>';
        }
        echo '</tr>';






// Création des rangées par heure
        for ($hour = 8; $hour <= 18; $hour++) {
            echo '<tr>';
            echo '<td class="time-slot">' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00</td>'; // Colonne des heures

            foreach ($daysOfWeek as $day) {
                $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);

                error_log("EVENTS : " . print_r($eventsByDayAndHour[$day][$hourKey], true));

                if (isset($eventsByDayAndHour[$day]) && isset($eventsByDayAndHour[$day][$hourKey])) {
                    echo '<td class="schedule-day">';
                    echo 'Ca marche';

                    foreach ($eventsByDayAndHour[$day][$hourKey] as $event) {
                        /*if ($event === 'covered') {
                            continue; // Ignore les créneaux horaires couverts
                        }
                        // Si l'heure est couverte, ne rien afficher ou continuer à la prochaine itération.
                        if ($this->isHourCoveredByRowspan($hour, $eventsByDayAndHour, $day)) {
                            continue; // ou echo '<td class="covered"></td>';
                        }*/

                        if (is_array($event)) {
                            // Affichage de chaque événement
                            echo '<div class="event" style="rowspan:' . htmlspecialchars($event['rowspan']) . ';">';
                            //error_log("ROWSPANNNN: " . print_r($event['rowspan']));
                            //error_log("START : " . print_r($event['start']));
                            echo htmlspecialchars($event['start'] . ' - ' . $event['end'] . ': ' . $event['summary']) . '<br>';
                            echo htmlspecialchars($event['location']) . '<br>';
                            echo htmlspecialchars($event['description']);
                            echo '</div>'; // Fin de l'événement

                        }
                    }


                } else {
                    echo '<td class="schedule-day"></td>';

                    //error_log("EVENTS : " . print_r($events, true));
                }
            }
                echo '</td>';
            }
            echo '</tr>';



        echo '</table>';
        echo '</div>';

        $content = ob_get_clean();
        include "layout.php";
        }



    private function getWeekDates($currentWeekDate, $daysOfWeek) {
        $weekDates = [];
        $baseTime = strtotime('Monday this week', strtotime($currentWeekDate));

        foreach ($daysOfWeek as $index => $day) {
            $timeStamp = strtotime("+$index days", $baseTime);
            $weekDates[$day] = strftime('%d/%m/%Y', $timeStamp);
        }

        return $weekDates;
    }

    private function isHourCoveredByRowspan($hour, $eventsByDayAndHour, $day) {
        foreach ($eventsByDayAndHour[$day] ?? [] as $eventStartHour => $eventsAtHour) {
            if (!is_array($eventsAtHour)) { // Si ce n'est pas un tableau, c'est probablement marqué comme 'covered'
                continue;
            }

            foreach ($eventsAtHour as $event) {
                if (is_array($event)) { // S'assurer que c'est bien un événement et non un marqueur 'covered'
                    $eventStart = (int) substr($event['start'], 0, 2);
                    $eventEnd = (int) substr($event['end'], 0, 2);

                    if ($hour >= $eventStart && $hour < $eventEnd) {
                        return true;
                    }
                }
            }
        }
        return false;
    }



    public function displayError($message) {
        echo '<p>Error: ' . htmlspecialchars($message) . '</p>';
    }


}
