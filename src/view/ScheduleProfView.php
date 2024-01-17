<?php

namespace src\view;

use DateTime;

class ScheduleProfView {

    public function displaySchedule($eventsByDayAndHour, $groupName, $currentWeekDate)
    {
        ini_set('display_errors', '0'); // Ne pas afficher les erreurs
        //error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
        error_reporting(E_ALL);

        ob_start();

        echo '<form method="GET" action="/index.php">';
        echo '<input type="hidden" name="action" value="navigateWeek">';
        if ($groupName == 'navigateWeek') {
            echo 'navigateWeek ne peut pas etre un group';
        }


        $weekStartDate = $this->getWeekStartDate($currentWeekDate);
        $previousWeekDate = date('Y-m-d', strtotime('last monday', strtotime($weekStartDate)));
        $nextWeekDate = date('Y-m-d', strtotime('next monday', strtotime($weekStartDate)));

        // Boutons de navigation de semaine avec les nouveaux liens
        echo '<a href="index.php?group=professeur&profName=' . urlencode($groupName) . '&week=prevWeek" class="navigation-button">Semaine précédente : ' . date('j F Y', strtotime($previousWeekDate)) . '</a>';
        echo '<a href="index.php?group=professeur&profName=' . urlencode($groupName) . '&week=nextWeek" class="navigation-button" style="margin-left: 30px;">Semaine suivante : ' . date('j F Y', strtotime($nextWeekDate)) . '</a>';
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

                if (!empty($eventsByDayAndHour[$day][$hourKey])) {
                    $eventsAtHour = $eventsByDayAndHour[$day][$hourKey];

                    // Affichage de la cellule principale
                    echo '<td class="schedule-day">';

                    // Utilisation d'un tableau imbriqué pour les sous-événements
                    echo '<table class="inner-table">';

                    foreach ($eventsAtHour as $event) {
                        $rowspan = $event['rowspan'];

                        // Création d'une rangée pour chaque sous-événement
                        echo '<tr>';
                        echo '<td rowspan="' . $rowspan . '" style="border: 1px solid #ddd; padding: 5px; margin: 2px;">';
                        // Affichage de l'événement
                        echo htmlspecialchars($event['start'] . ' - ' . $event['end'] . ' : ' . $event['summary']) . '<br>';
                        echo htmlspecialchars($event['location']) . '<br>';
                        echo htmlspecialchars($event['description']);
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
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

    private function getWeekStartDate($currentWeekDate) {
        return date('Y-m-d', strtotime('Monday this week', strtotime($currentWeekDate)));
    }

    public function displayError($message) {
        echo '<p>Error: ' . htmlspecialchars($message) . '</p>';
    }


}
