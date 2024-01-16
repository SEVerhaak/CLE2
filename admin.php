<!-- Verbinding met de Database maken en de reserveringen ophalen -->
<?php
/** @var mysqli $db */

require_once 'includes/database.php';

$query = "SELECT * FROM reservations";

$result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

$reservations = [];

while ($reservation = mysqli_fetch_assoc($result)) {
    $reservations[] = $reservation;
}

$queryAdminBox = "SELECT reservationDate, amountPeople, reservationType FROM reservations ORDER BY reservationDate LIMIT 2";
$resultAdminBox = mysqli_query($db, $queryAdminBox) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

$reservationsAdminBox = [];

while ($reservationAdminBox = mysqli_fetch_assoc($resultAdminBox)) {
    $reservationsAdminBox[] = $reservationAdminBox;
}
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
    <link href="css/admin.css" rel="stylesheet">
    <link href="css/example.css" rel="stylesheet">
    <link href="css/calendar.css" rel="stylesheet">
    <title>Denise Kookt!</title>
</head>
<!-- Header -->
<header>
    <nav>
        <div class="nav-right">
            <img class="logo" src="img/logo_dk.png">
            <a class="header-link-text" href="reservation.php">Reserveren</a>
            <a class="header-link-text" href="about.php">Over ons</a>
            <a class="header-link-text" href="news.php">Nieuws</a>
            <a class="header-link-text" href="contact.php">Contact</a>
        </div>
        <div class="nav-left">
            <a class="login" href="admin.php">Login</a>
        </div>
    </nav>
</header>
<body>
<div class="sidebar">
    <a href="index.php"><img src="img/home.png"></a>
    <a href="#mail"><img src="img/mail.png"></a>
    <a href="#calender"><img src="img/agenda.png"></a>
    <a href="#money"><img src="img/dollar.png"></a>
    <a href="settings.php"><img src="img/settings.png"></a>
</div>
<div class="admin-box">
    <section class="admin-section1">
        <div class="admin-reservations">
            <h1>Openstaande reserveringen</h1>
            <div class="admin-reservation">
                <h2><?= $reservationsAdminBox[0]["reservationDate"] ?></h2>
                <div>
                    <p><?php echo $reservationsAdminBox[0]["amountPeople"] . ' personen, ' . $reservationsAdminBox[0]["reservationType"] ?> </p>
                    <a href="#reservations"> Details </a>
                </div>
            </div>
            <div class="admin-reservation">
                <h2><?= $reservationsAdminBox[1]["reservationDate"] ?></h2>
                <div>
                    <p><?php echo $reservationsAdminBox[0]["amountPeople"] . ' personen, ' . $reservationsAdminBox[0]["reservationType"] ?> </p>
                    <a href="#reservations"> Details </a>
                </div>
            </div>
        </div>
        <div class="admin-reservations">
            <h1>Agenda</h1>
            <div class = "calender">
                <img  src = "img/Buttonright.png">
                <img href = "agenda.php" ><img  src = "img/calender.png" class = "calender_img"></a>
                <img  src = "img/buttonleft.png">
            </div>
        </div>
    </section>
    <section class="admin-section2">
        <h1>Welkom, admin</h1>
        <div class = "admin-text">
            <p>Openstaande reserveringen: <?= count($reservations) ?></p>
            <a href = "#reserveringen">Ga naar reserveringen</a>
            <p>Nieuwe berichten: 5</p>
            <a href = "#reserveringen">Ga naar mail</a>
        </div>
    </section>
    <section class="admin-section3">
        <h1>Berichten</h1>
            <div class = "message">
                <div class = "person">
                    <h1> P.J.</h1>
                </div>
                <div>
                    <h2>Aanvullende informatie over levering</h2>
                    <p>Beste catering service, ik zou graag meer informatie ontvangen over de leveringsopties en eventuele kosten voor een bestelling op [datum].</p>
                </div>
            </div>
            <div class = "message">
                <div class = "person">
                    <h1> P.J.</h1>
                </div>
                <div>
                    <h2>Offerte aanvragen voor bedrijfslunch</h2>
                    <p>Beste catering service, kunt u mij een offerte sturen voor een bedrijfslunch op [datum] voor ongeveer [aantal] personen?</p>
                </div>
            </div>
            <div class = "message">
                <div class = "person">
                    <h1> P.J.</h1>
                </div>
                <div>
                    <h2>Beschikbaarheid checken voor evenement</h2>
                    <p>Hallo catering team, kunnen jullie bevestigen of jullie beschikbaar zijn op [datum] voor ons evenement?</p>
                </div>
            </div>
            <div class = "message">
                <div class = "person">
                    <h1> P.J.</h1>
                </div>
                <div>
                    <h2>Menu-opties voor feest</h2>
                    <p>Beste cateraar, graag ontvang ik informatie over jullie menu-opties en prijzen voor een feest op [datum].</p>
                </div>
            </div>
        <div class = "message">
            <div class = "person">
                <h1> P.J.</h1>
            </div>
            <div>
                <h2>Dieetwensen bespreken</h2>
                <p>Hallo catering team, kunnen jullie rekening houden met specifieke dieetwensen voor een catering op [datum]?</p>
            </div>
        </div>
        <a href = "#messages">Alle mails</a>
    </section>
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
