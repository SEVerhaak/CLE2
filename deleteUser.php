<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
$userId = $_GET['id'];
/** @var mysqli $db */

require_once 'includes/database.php';

$query = "DELETE FROM `users` WHERE id = '$userId';";

$result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));



// SQL-query
mysqli_close($db);
header('Location: users.php')
?>