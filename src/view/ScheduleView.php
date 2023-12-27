<?php

namespace src\view;

class ScheduleView {

    public function displaySchedule($events) {
        ob_start();

        $html = '<table class="schedule-table">'; // Utiliser une classe pour le style CSS
        $html .= '<tr><th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th></tr>';

        // Préparation des données pour chaque jour
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $html .= '<tr>';
        foreach ($daysOfWeek as $day) {
            $html .= '<td>';
            if (isset($events[$day]) && count($events[$day]) > 0) {
                foreach ($events[$day] as $event) {
                    $html .= htmlspecialchars($event['start'] . ' - ' . $event['end'] . ': ' . $event['summary']) .
                        $event['location'] . $event['description']  .'<br>';
                }
            } else {
                $html .= 'Pas cours'; // Affichage si aucun evenement
            }
            $html .= '</td>';
        }
        $html .= '</tr>';

        $html .= '</table>';

        echo $html;
        $content = ob_get_clean();
        include "layout.php";
    }


    public function displayError($message) {
        echo '<p>Error: ' . htmlspecialchars($message) . '</p>';
    }
}
