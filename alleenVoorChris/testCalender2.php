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

$sqlQuery1 = "SELECT CONCAT(reservationType, ' ', DATE_FORMAT(reservationBeginTime, '%H:%i')) AS planningCatering, reservationDate, id FROM reservations WHERE reservationType = 'catering'";
$result1 = $db->query($sqlQuery1);

$sqlQuery2 = "SELECT CONCAT(reservationType, ' ', DATE_FORMAT(reservationBeginTime, '%H:%i')) AS planningWorkshop, reservationDate, id FROM reservations WHERE reservationType = 'workshop'";
$result2 = $db->query($sqlQuery2);

$sqlQuery3 = "SELECT id, reservationDate FROM reservations WHERE userId = 1";
$result3 = $db->query($sqlQuery3);




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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
                <a class="login" href = "user-reservations.php">Mijn reserveringen</a>
            <?php } ?>
        </div>
    </nav>
</header>
<body>
<?php if(isset($_SESSION['user']['admin'])){ ?>
    <div class="sidebar">
        <a href="admin.php"><img src="img/home.png"></a>
        <a href="users.php"><img src="img/users.png"></a>
        <a href="testCalender2.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
    </div>

<?php } ?>
<div class="calender-box">

    <!-- Evenementen aan de kalender toevoegen (moet nog verbonden worden met de database) -->
    <?php
    include 'calender2.0.php';
    $calendar = new Calendar();






    // Check of er resultaten zijn en loop door de resultaten
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            // Haal de geplande gegevens op
            $planning = $row["planningCatering"];

            //Haal de reserveringsdatum op
            $reservationDate = $row["reservationDate"];

            //Haal de id van de afspraak op
            $appointmentId = $row["id"];

            // Stel hier je gewenste URL in
            $url = "appointment_details.php?id=" . $appointmentId;

            // Voeg de geplande gegevens toe aan de kalender
            $calendar->add_event($planning, $reservationDate, 1, 'green', $url);
        }
    } else {
        echo "Geen resultaten gevonden.";
    }

    // Check of er resultaten zijn en loop door de resultaten
    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            // Haal de geplande gegevens op
            $planning = $row["planningWorkshop"];

            //Haal de reserveringsdatum op
            $reservationDate = $row["reservationDate"];

            //Haal de id van de afspraak op
            $appointmentId = $row["id"];

            // Stel hier je gewenste URL in
            $url = "appointment_details.php?id=" . $appointmentId;

            // Voeg de geplande gegevens toe aan de kalender
            $calendar->add_event($planning, $reservationDate, 1, 'blue', $url);
        }
    } else {
        echo "Geen resultaten gevonden.";
    }

    // Check of er resultaten zijn en loop door de resultaten
    if ($result3->num_rows > 0) {
        while ($row = $result3->fetch_assoc()) {
            // Haal de geplande gegevens op
            $nietBeschikbaar = $row["reservationDate"];

            // Gebruik de daadwerkelijke ID-kolomnaam uit de tabel
            $appointmentId = $row["id"];

            // Voeg de geplande gegevens toe aan de kalender
            $calendar->add_event('Niet beschikbaar', $nietBeschikbaar, 1, 'red');
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
        <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>
