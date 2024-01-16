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
    <a href="#home"><img src = "img/home.png"></a>
    <a href="#mail"><img src = "img/mail.png"></a>
    <a href="#calender"><img src = "img/agenda.png"></a>
    <a href="#money"><img src = "img/dollar.png"></a>
    <a href="#settings"><img src = "img/settings.png"></a>
</div>
<div class="admin-box">
    <!-- Tabel met reserveringen uit de Database -->
    <!--
    <div class="table">
        <table border="1">
            <thead>
            <tr>
                <?php
                foreach (array_keys($reservations[0]) as $columnName) {
                    echo '<th>' . $columnName . '</th>';
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <?php foreach ($reservation as $value): ?>
                        <td><?php echo $value; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
     Evenementen aan de kalender toevoegen (moet nog verbonden worden met de database)
    <?php
    include 'Calendar.php';
    $calendar = new Calendar();
    $calendar->add_event('Workshop 18:00', '2024-01-03', 1, 'green');
    $calendar->add_event('Catering 17:00', '2024-01-07', 1, 'yellow');
    $calendar->add_event('Workshop 16:30', '2024-01-23', 1, 'green');
    $calendar->add_event('Catering 17:30', '2024-01-31', 1, 'yellow');
    ?>
    Kalender met navigatieknoppen
    <div class="content home">
        <a href="?month=<?= $calendar->getPrevMonth() ?>" class="linkOne">Previous Month</a>
        <a href="?month=<?= $calendar->getNextMonth() ?>" class="linkTwo">Next Month</a>
        <?= $calendar ?>
    </div>
    Footer
    -->
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
