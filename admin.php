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
    <link href="css/admin.css" rel="stylesheet" >
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
            <a class="header-link-text" href="#">Over ons</a>
            <a class="header-link-text" href="#">Nieuws</a>
            <a class="header-link-text" href="#">Contact</a>
        </div>
        <div class="nav-left">
            <a class="login" href = "#">Login</a>
        </div>
    </nav>
</header>
<body>
<!-- Tabel met reserveringen uit de Database -->
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
<!-- Variabelen voor de Tabel -->
<?php
$time = time();
$numDay = date('d', $time);
$numMonth = date('m', $time);
$strMonth = date('F', $time);
$numYear = date('Y', $time);
$firstDay = mktime(0,0,0,$numMonth,1,$numYear);
$daysInMonth = cal_days_in_month(0, $numMonth, $numYear);
$dayOfWeek = date('w', $firstDay);
?>
<!-- Tabel met reserveringen uit de Database -->
<table>
    <caption><?php echo($strMonth); ?></caption>
    <thead>
    <tr>
        <th abbr="Sunday" scope="col" title="Sunday">S</th>
        <th abbr="Monday" scope="col" title="Monday">M</th>
        <th abbr="Tuesday" scope="col" title="Tuesday">T</th>
        <th abbr="Wednesday" scope="col" title="Wednesday">W</th>
        <th abbr="Thursday" scope="col" title="Thursday">T</th>
        <th abbr="Friday" scope="col" title="Friday">F</th>
        <th abbr="Saturday" scope="col" title="Saturday">S</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php
        if(0 != $dayOfWeek) { echo('<td colspan="'.$dayOfWeek.'"> </td>'); }
        for($i=1;$i<=$daysInMonth;$i++) {

            if($i == $numDay) { echo('<td id="today">'); } else { echo("<td>"); }
            echo($i);
            echo("</td>");
            if(date('w', mktime(0,0,0,$numMonth, $i, $numYear)) == 6) {
                echo("</tr><tr>");
            }
        }
        ?>
    </tr>
    </tbody>
</table>
<!-- Footer -->
</body>
<footer>
    <div class = "footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
</html>
