
<?php
session_start();

/** @var mysqli $db */

require_once 'includes/database.php';
require 'includes/functions.php';

adminCheck();

$query = "SELECT reservations.id, amountPeople, reservationDate, reservationBeginTime, reservationEndTime, reservationType, extraInfo, users.firstName, users.lastName, users.phoneNumber, users.email FROM `reservations` JOIN users on userId = users.id WHERE amountPeople != '0' ORDER by reservationDate ";
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
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php" class="selected-admin"><img src="img/dollar.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>
<label for="search"></label>
<div class="searchbar">
<input type="text" id="search" onkeyup="searchResults()" placeholder="Zoeken..." style="margin-left: 5rem;">
</div>
<div class="info-reservation-box">

    <?php foreach ($reservations as $index => $reservation) { ?>
        <div class="info-reservation" id="<?= 'resultBox' . $index ?>">
            <div class="resulWrapper" data-display="0">
                <h2 class="resultTitle">Datum reservering: </h2>
                <h2 class="result"
                    id="<?= 'dateResult' . $index ?>"> <?= date("D F j, Y", strtotime($reservations[$index]['reservationDate']))?></h2>
            </div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">Reservering op naam:</p>
                <p class="result"
                   id="<?= 'firstNameResult' . $index ?>"> <?= $reservations[$index]['firstName'] . ' ' . $reservations[$index]['lastName'] ?></p>
            </div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">Hoeveelheid mensen: </p>
                <p class="result"
                   id="<?= 'amountPeopleResult' . $index ?>"> <?= $reservations[$index]['amountPeople'] ?> </p></div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">Type reservering: </p>
                <p class="result" id="<?= 'typeResult' . $index ?>"> <?= $reservations[$index]['reservationType'] ?></p>
            </div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">Tijd reservering: </p>
                <p class="result"
                   id="<?= 'timeResult' . $index ?>"> <?= $reservations[$index]['reservationBeginTime'] . '-' . $reservations[$index]['reservationEndTime'] ?></p>
            </div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">E-mail reserveerder:</p>
                <p class="result" id="<?= 'emailResult' . $index ?>"> <?= $reservations[$index]['email'] ?></p></div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">Tel reserveerder: </p>
                <p class="result" id="<?= 'phoneResult' . $index ?>"><?= '0'.$reservations[$index]['phoneNumber'] ?></p>
            </div>
            <div class="resulWrapper" data-display="0">
                <p class="resultTitle">Bijzonderheden: </p>
                <p class="result" id="<?= 'extraInfoResults' . $index ?>"><?= $reservations[$index]['extraInfo'] ?></p>
            </div>
            <a href="delete.php?id=<?= $reservations[$index]['id'] ?>">Verwijder reservering</a>
            <a href="edit.php?id=<?= $reservations[$index]['id'] ?>">Verander reservering</a>
        </div>
    <?php } ?>
</div>
</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
<script>
    // text elementen die doorzocht gaan worden met de zoek functie
    let elementsToSearch = document.getElementsByClassName('result');
    // lengte voor de loop bepalen (dit gaat met de titel inplaats van de resultaten want sommige resultaten zijn leeg en dan klopt de lengte niet)
    let elementsToHide = document.getElementsByClassName('resultTitle');
    // het input element waaruit de string voor het zoeken uit gehaald wordt
    let input = document.getElementById('search');

    // functie voor het zoeken
    function searchResults() {
        // zet de ingevulde waarde om in alleen hoofdletters
        let filter = input.value.toUpperCase();
        // parent element wat verborgen moet worden als alle children onzichtbaar zijn
        let boxDiv = document.getElementsByClassName('info-reservation')
        // de div die om de 2e elementen zit, door deze te verbergen verdwijnen beide elementen
        let wrapElement = document.getElementsByClassName('resulWrapper')
        // for loop om te zoeken
        for (let i = 0; i < elementsToSearch.length; i++) {
            // zet tekst van element[i] om in hoofdletters
            let text = elementsToSearch[i].innerHTML.toUpperCase();
            // als de ingevoerde tekst overeen komt met de tekst uit element[i], zet de display dan op de standaard waarde
            if (text.indexOf(filter) > -1) {
                wrapElement[i].style.display = '';
                //wrapElement[i].setAttribute('data-display', '0')
                // anders als de ingevoerde tekst niet overeen komt verberg element[i]
            } else {
                wrapElement[i].style.display = 'none';
                // wrapElement[i].setAttribute('data-display', '1')
            }
        }
        // for loop om te kijken of alle children van de omhullende div onzichtbaar zijn, als ze allemaal onzichtbaar zijn wordt de parent div ook onzichtbaar gemaakt.
        for (let j = 0; j < boxDiv.length; j++) {
            // aan het begin van de loop zet de counters op 0
            let visibleCount = 0;
            let inVisibleCount = 0;
            // start de volgende loop, deze loopt door alle children van het parent element en kijkt dan of deze onzichtbaar
            for (let k = 0; k < boxDiv[j].children.length; k++) {
                if (boxDiv[j].children[k].style.display === ''){
                    // als child[k] van div[j] zichtbaar is dan +1 visible count
                    visibleCount += 1;
                } else {
                    // als child[k] van div[j] niet zichtbaar is dan +1 voor invisible count
                    inVisibleCount += 1;
                }
            }
            // als visiblecount gelijk is aan 2 zijn alleen de buttons in de div nog zichtbaar en kan de hele div ontzichtbaar gemaakt worden
            if (visibleCount === 2){
                boxDiv[j].style.display = 'none'
            } else {
                boxDiv[j].style.display = ''
            }
        }

    }
</script>
</html>
<?php
