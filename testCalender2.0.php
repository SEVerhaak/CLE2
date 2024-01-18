<?php
/** @var mysqli $db */

require_once 'includes/database.php';


function generateCalendar($year, $month, $db) {
    // Eerste dag van de maand
    $firstDay = date("w", mktime(0, 0, 0, $month, 1, $year));

    // Aantal dagen in de maand
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Begin van de kalender
    echo "<table border='1'>";
    echo "<tr>";

    // Dagen van de week als koptekst
    echo "<th>Zo</th><th>Ma</th><th>Di</th><th>Wo</th><th>Do</th><th>Vr</th><th>Za</th>";

    echo "</tr><tr>";

    // Lege cellen tot de eerste dag van de maand
    for ($i = 0; $i < $firstDay; $i++) {
        echo "<td></td>";
    }

    // Dagen van de maand
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $currentDate = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));

        // Controleren op reserveringen voor de huidige dag
        $query = "SELECT resrvationDate FROM reservations'";
        $result = $db->query($query);
        $row = $result->fetch_assoc();
        $reservationCount = $row['count'];

        // Voeg een klasse toe aan de cel als er reserveringen zijn
        $cellClass = ($reservationCount > 0) ? 'reserved-day' : '';

        echo "<td class='$cellClass'>$day</td>";

        // Wissel naar een nieuwe rij na elke 7 dagen (dagen van de week)
        if (($firstDay + $day) % 7 == 0) {
            echo "</tr><tr>";
        }
    }

    // Vul de laatste rij met lege cellen als dat nodig is
    $remainingDays = 7 - (($firstDay + $daysInMonth) % 7);
    for ($i = 0; $i < $remainingDays; $i++) {
        echo "<td></td>";
    }

    echo "</tr></table>";
}

// Huidige jaar en maand
$currentYear = date("Y");
$currentMonth = date("n");

// Genereer de kalender voor de huidige maand
generateCalendar($currentYear, $currentMonth, $db);


?>
