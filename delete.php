<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
$reservationId = $_GET['id'];
$page = $_GET['page'];
/** @var mysqli $db */

require_once 'includes/database.php';

$query = "DELETE FROM `reservations` WHERE id = '$reservationId';";

$result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));



// SQL-query
mysqli_close($db);
if($page == 'adminSelectDates'){
    header('Location: adminSelectDates.php');
}else {
    header('Location: admin_reservations.php');
    }
?>