<?php
/** @var array $db */
require_once 'includes/database.php';

function takenDatesCheckerDataFetch($db){
    $query = "SELECT `id`, `userId`, `reservationDate`, `reservationBeginTime`, `reservationEndTime` FROM `reservations` WHERE 1";
    $result = mysqli_query($db, $query)
    or die('Error '.mysqli_error($db).' with query '.$query);

    $reservations = [];
    $takenDates = [];
    while($row = mysqli_fetch_assoc($result))
        $reservations[] = $row;
    for ($i = 0; $i < count($reservations); $i++) {
        array_push($takenDates, $reservations[$i]['reservationDate']);
    }
    return $takenDates;
}
?>