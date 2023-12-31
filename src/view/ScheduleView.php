<?php

namespace src\view;

class ScheduleView {

    public function displaySchedule($events, $groupName) {
        ob_start();
        // Ajoutez ici le formulaire de navigation
        echo '<form method="GET" action="/index.php">';
        echo '<input type="hidden" name="action" value="navigateWeek">';
        if ($groupName=='navigateWeek'){
            echo 'navigateWeek ne peut pas etre un group';
        }
        echo '<input type="hidden" name="group" value="' . htmlspecialchars($groupName) . '">';
        echo '<button type="submit" name="week" value="prevWeek">Semaine précédente</button>';
        echo '<button type="submit" name="week" value="nextWeek">Semaine suivante</button>';

        echo '</form>';

        echo '<div class="schedule-container">';
        echo '<table class="schedule-table">';
        echo '<tr><th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th></tr>';

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        foreach ($daysOfWeek as $day) {
            echo '<td>';
            if (isset($events[$day]) && count($events[$day]) > 0) {
                foreach ($events[$day] as $event) {
                    echo htmlspecialchars($event['start'] . ' - ' . $event['end'] . ': ' . $event['summary']) .
                        htmlspecialchars($event['location']) . htmlspecialchars($event['description']) . '<br>';
                }
            } else {
                echo 'Pas cours';
            }
            echo '</td>';
        }

        echo '</table>';
        echo '</div>';

        $content = ob_get_clean();
        include "layout.php";
    }
    // ScheduleView.php
    public function displayAvailableRooms($availableRooms) {
        include 'ButSalles.php';
    }



    public function displayError($message) {
        echo '<p>Error: ' . htmlspecialchars($message) . '</p>';
    }
}
