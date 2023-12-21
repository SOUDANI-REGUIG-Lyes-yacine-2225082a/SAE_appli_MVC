<?php



class ScheduleView {

    public function displaySchedule($events) {
        $html = '<table border="1">';
        $html .= '<tr><th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th></th></tr>';

        // Préparation des données pour chaque jour
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $html .= '<tr>';
        foreach ($daysOfWeek as $day) {
            $html .= '<td>';
            if (isset($events[$day])) {
                foreach ($events[$day] as $event) {
                    $html .= htmlspecialchars($event['start'] . ' - ' . $event['end'] . ': ' . $event['summary']) . '<br>';
                }
            }
            $html .= '</td>';
        }
        $html .= '</tr>';

        $html .= '</table>';

        echo $html;
    }

    public function displayError($message) {
        echo '<p>Error: ' . htmlspecialchars($message) . '</p>';
    }
}
