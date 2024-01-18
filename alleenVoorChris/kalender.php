<?php
// Verbinding met de database (vervang de waarden met je eigen database-informatie)
/** @var mysqli $db */

require_once 'includes/database.php';

class Calendar {
    private $events = array();

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function generate_calendar($year, $month) {
        $calendar = '<table border="1">
                        <thead>
                            <tr>
                                <th>Zo</th>
                                <th>Ma</th>
                                <th>Di</th>
                                <th>Wo</th>
                                <th>Do</th>
                                <th>Vr</th>
                                <th>Za</th>
                            </tr>
                        </thead>
                        <tbody>';

        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $lastDay = mktime(0, 0, 0, $month + 1, 0, $year);
        $currentDay = $firstDay;

        while ($currentDay <= $lastDay) {
            $calendar .= '<tr>';

            for ($i = 0; $i < 7; $i++) {
                $day = date('j', $currentDay);
                $date = date('Y-m-d', $currentDay);

                $calendar .= '<td>';

                // Voeg hier de logica toe voor het weergeven van de link
                $link = $this->get_link_for_date($date);
                if ($link) {
                    $calendar .= '<a href="' . $link . '">' . $day . '</a>';
                } else {
                    $calendar .= $day;
                }

                $calendar .= '</td>';

                $currentDay += 86400; // Voeg een dag toe aan $currentDay
            }

            $calendar .= '</tr>';
        }

        $calendar .= '</tbody></table>';
        echo $calendar;
    }

    private function get_link_for_date($date) {
        global $db;

        $sql = "SELECT CONCAT(reservationType, ' ', DATE_FORMAT(reservationBeginTime, '%H:%i')) AS planningCatering, reservationDate FROM reservations WHERE reservationType = 'catering';";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return 'appointment_details.php?type=' . $row['planningCatering'];
        }

        return ''; // Retourneer een lege string als er geen link is voor deze datum
    }
}

// Voorbeeldgebruik:
$calendar = new Calendar();
$calendar->generate_calendar(2024, 1);


?>
