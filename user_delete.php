<?php
require 'includes/functions.php';

adminCheck();
$reservationId = $_GET['id'];
/** @var mysqli $db */

require_once 'includes/database.php';

$query = "DELETE FROM `reservations` WHERE id = '$reservationId';";

$result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));



// SQL-query
mysqli_close($db);

header('Location: user-reservations.php');

?>