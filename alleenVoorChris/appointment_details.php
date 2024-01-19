<?php
/** @var mysqli $db */

require_once 'includes/database.php';

// Selecteert de ID van de sneakers
$id = mysqli_escape_string($db, $_GET['id'])  ;

$query = "SELECT r.userId, r.reservationDate, r.amountPeople, DATE_FORMAT(r.reservationBeginTime, '%H:%i') AS beginTime, DATE_FORMAT(r.reservationEndTime, '%H:%i') AS endTime, r.reservationType, r.extraInfo, u.firstName, u.lastName
            FROM reservations r
            INNER JOIN users u ON r.userId = u.id
            WHERE r.userId = $id";

// Maakt connectie met de database en voert de query uit, anders stoppen met connecten en foutmeldingn tonen
$result = mysqli_query($db, $query) or die('Error ' . mysqli_error($db) . ' with query ' . $query);

// query die wordt opgehaald doormiddel van mysqli_fetch_assoc wordt met een array opgeslagen in var album
$info = mysqli_fetch_assoc($result);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<section class="content">
    <ul>
        <li>User Id <?php echo htmlentities($info['userId'])?></li>
        <li>Aantal personen <?php echo htmlentities($info['amountPeople'])?></li>
        <li>Gereseveerde datum <?php echo htmlentities($info['reservationDate'] )?></li>
        <li>Begin tijd: <?php echo htmlentities($info['beginTime'])?></li>
        <li>Eind Tijd: <?php echo htmlentities($info['endTime'])?></li>
        <li>Extra informatie <?php echo htmlentities($info['extraInfo'])?></li>

    </ul>
</section>
</body>
</html>
