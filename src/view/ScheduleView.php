<?php

namespace src\view;

use DateTime;

class ScheduleView {

    public function displaySchedule($eventsByDayAndHour, $groupName, $currentWeekDate)
    {
        ini_set('display_errors', '0'); // Ne pas afficher les erreurs
        //error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
        error_reporting(E_ALL);

        ob_start();
        // Ajoutez ici le formulaire de navigation
        echo '<form method="GET" action="/index.php">';
        echo '<input type="hidden" name="action" value="navigateWeek">';
        if ($groupName == 'navigateWeek') {
            echo 'navigateWeek ne peut pas etre un group';
        }
        echo '<input type="hidden" name="group" value="' . htmlspecialchars($groupName) . '">';
        echo '<button type="submit" name="week" value="prevWeek" class="navigation-button">Semaine précédente</button>';
        echo '<button type="submit" name="week" value="nextWeek" class="navigation-button">Semaine suivante</button>';

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


        $coveredHours = [];

        // Création des rangées par heure
        for ($hour = 8; $hour <= 18; $hour++) {
            echo '<tr>';
            echo '<td class="time-slot">' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00</td>'; // Colonne des heures

            foreach ($daysOfWeek as $day) {
                $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);

                // Vérifiez si des événements sont prévus pour cette heure et ce jour
                if (isset($eventsByDayAndHour[$day][$hourKey]) && !empty($eventsByDayAndHour[$day][$hourKey])) {
                    $eventsAtHour = $eventsByDayAndHour[$day][$hourKey];
                    $totalEvents = count($eventsAtHour);

                    // Création de la cellule qui contiendra tous les événements
                    echo '<td class="schedule-day">';
                    echo '<div style="display: flex; width: 100%; height: 100%;">';

                    // Boucle sur chaque événement pour cette heure et ce jour
                    foreach ($eventsAtHour as $event) {
                        // Affichage de chaque événement dans sa propre sous-cellule
                        echo '<div style="flex: 1; border: 1px solid #ddd; padding: 5px; margin: 2px;">';
                        echo htmlspecialchars($event['start'] . ' - ' . $event['end'] . ': ' . $event['summary']) . '<br>';
                        echo htmlspecialchars($event['location']) . '<br>';
                        echo htmlspecialchars($event['description']);
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '</td>';
                } else {
                    // Affiche une cellule vide si aucun événement
                    echo '<td class="schedule-day"></td>';
                }
            }

            echo '</tr>';
        }






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
            //$weekDates[$day] = strftime('%d/%m/%Y', $timeStamp);
            $weekDates[$day] = (new DateTime())->setTimestamp($timeStamp)->format('d/m/Y');
        }

        return $weekDates;
    }
    // ScheduleView.php
    public function displayAvailableRooms($availableRooms) {
        include 'ButSalles.php';
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
