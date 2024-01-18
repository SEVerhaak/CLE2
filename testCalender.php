<!-- Verbinding met de Database maken en de reserveringen ophalen -->
<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
/** @var mysqli $db */

require_once 'includes/database.php';

$query = "SELECT * FROM reservations";

$result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

$reservations = [];

while ($reservation = mysqli_fetch_assoc($result)) {
    $reservations[] = $reservation;
}

// Sluit de resultaatset, maar laat de verbinding open
mysqli_free_result($result);


// SQL-query
$sqlQuery = "SELECT reservationDate FROM reservations";
$result = $db->query($sqlQuery);

$sqlQuery1 = "SELECT CONCAT(reservationType, ' ', reservationBeginTime) AS planning FROM reservations";
$result1 = $db->query($sqlQuery1);





mysqli_close($db);
?>
<!-- Documentinformatie en CSS connectie -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/example.css" rel="stylesheet">
    <title>Denise Kookt!</title>
</head>
<!-- Header -->
<header>
    <nav>
        <div class="nav-right">
            <a href = "index.php"><img class="logo" src="img/logo_dk.png"></a>
            <div class = "header-links">
                <a class="header-link-text" href="reservation.php">Reserveren</a>
                <a class="header-link-text" href="about.php">Over ons</a>
                <a class="header-link-text" href="news.php">Nieuws</a>
                <a class="header-link-text" href="contact.php">Contact</a>
            </div>
        </div>
        <div class="nav-left">
            <?php if(!isset($_SESSION['user'])){?>
                <a class="login" href="login.php">Login</a>
            <?php }else{ ?>
                <a class="login" href = "logout.php">Log uit</a>
            <?php } ?>
        </div>
    </nav>
</header>
<body>
<?php if(isset($_SESSION['user']['admin'])){ ?>
    <div class="sidebar">
        <a href="admin.php"><img src="img/home.png"></a>
        <a href="#mail"><img src="img/mail.png"></a>
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>
<div class="calender-box">


    <!-- Evenementen aan de kalender toevoegen (moet nog verbonden worden met de database) -->
    <?php
    include 'calender2.0.php';
    $calendar = new Calendar();



    // Check of er resultaten zijn en voeg ze toe aan de kalender
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Haal de reserveringsdatum op
            $reservationDate = $row["reservationDate"];

            // Voeg de reserveringsdatum toe aan de kalender
            $calendar->add_event('Reservering', $reservationDate, 1, 'green');
        }
    } else {
        echo "Geen resultaten gevonden.";
    }





    ?>
    <!-- Kalender met navigatieknoppen -->
    <div class="content-home">
        <a href="?month=<?= $calendar->getPrevMonth() ?>" class="linkOne">Previous Month</a>

        <?= $calendar ?>
        <a href="?month=<?= $calendar->getNextMonth() ?>" class="linkTwo">Next Month</a>
    </div>
    <!-- Footer -->
</div>
</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
</html>