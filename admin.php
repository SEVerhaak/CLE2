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
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <title>Denise Kookt!</title>
</head>
<header>
    <nav>
        <div class="nav-right">
            <img class="logo" src="img/logo_denisekookt.png">
            <a class="header-link-text" href="#">Reserveren</a>
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

<div class="month">
    <ul>
        <li class="prev">&#10094;</li>
        <li class="next">&#10095;</li>
        <li>January<br><span style="font-size:18px">2024</span></li>
    </ul>
</div>

<?php
include 'Calendar.php';
$calendar = new Calendar();
?>

</body>
<footer>
    <div class = "footer-style">
        <img class="logo" src="img/logo_denisekookt.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
</html>
