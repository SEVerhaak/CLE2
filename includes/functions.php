<?php
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
    print_r($takenDates);
}
?>