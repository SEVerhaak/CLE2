
<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
/** @var mysqli $db */

require_once 'includes/database.php';

$query = "SELECT reservations.id, amountPeople, reservationDate, reservationBeginTime, reservationEndTime, reservationType, extraInfo, users.firstName, users.lastName, users.phoneNumber, users.email FROM `reservations` JOIN users on userId = users.id ORDER by reservationDate";

$result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

$reservations = [];

while ($reservation = mysqli_fetch_assoc($result)) {
    $reservations[] = $reservation;
}

// Sluit de resultaatset, maar laat de verbinding open
mysqli_free_result($result);


// SQL-query




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
<div class="info-reservation-box">
    <?php foreach($reservations as $index => $reservation){ ?>
    <div class="info-reservation">
        <h2>Datum reservering: <?= $reservations[$index]['reservationDate'] ?></h2>
        <p>Reservering op naam: <?= $reservations[$index]['firstName'].' '.$reservations[0]['lastName']?></p>
        <p>Hoeveelheid mensen: <?= $reservations[$index]['amountPeople']?></p>
        <p>Type reservering: <?= $reservations[$index]['reservationType']?></p>
        <p>E-mail reserveerder: <?= $reservations[$index]['email']?></p>
        <p>Tel reserveerder: <?= $reservations[$index]['phoneNumber']?></p>
        <p>Bijzonderheden: <?= $reservations[$index]['extraInfo']?></p>
        <a href = "delete.php?id=<?= $reservations[$index]['id']?>">Verwijder reservering</a>
        <a href = "edit.php">Verander reservering</a>
    </div>
<?php } ?>
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
<?php
