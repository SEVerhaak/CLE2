<!-- Verbinding met de Database maken en de reserveringen ophalen -->
<?php
session_start();
if(!isset($_SESSION['user']['admin'])){
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

$queryAdminBox = "SELECT reservationDate, amountPeople, reservationType, id FROM reservations ORDER BY reservationDate LIMIT 2";
$resultAdminBox = mysqli_query($db, $queryAdminBox) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

$reservationsAdminBox = [];

while ($reservationAdminBox = mysqli_fetch_assoc($resultAdminBox)) {
    $reservationsAdminBox[] = $reservationAdminBox;
}
$queryUsers = "SELECT * FROM users";

$resultUser = mysqli_query($db, $queryUsers) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

$users = [];

while ($user = mysqli_fetch_assoc($resultUser)) {
    $users[] = $user;
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
        <a href="users.php"><img src="img/users.png"></a>
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
    </div>
<?php } ?>
<div class="admin-box">
    <section class="admin-section1">
        <div class="admin-reservations">
            <h1>Openstaande reserveringen</h1>
            <div class="admin-reservation">
                <h2><?= $reservationsAdminBox[0]["reservationDate"] ?></h2>
                <div>
                    <p><?php echo $reservationsAdminBox[0]["amountPeople"] . ' personen, ' . $reservationsAdminBox[0]["reservationType"] ?> </p>
                    <a href="edit.php?id=<?=$reservationsAdminBox[0]["id"]?>"> Details </a>
                </div>
            </div>
            <div class="admin-reservation">
                <h2><?= $reservationsAdminBox[1]["reservationDate"] ?></h2>
                <div>
                    <p><?php echo $reservationsAdminBox[1]["amountPeople"] . ' personen, ' . $reservationsAdminBox[0]["reservationType"] ?> </p>
                    <a href="edit.php?id=<?=$reservationsAdminBox[1]["id"]?>"> Details </a>
                </div>
            </div>
        </div>
        <div class="admin-reservations">
            <h1>Agenda</h1>
            <div class = "calender">
                <img  src = "img/leftButton.png">
                <a href = "testCalender.php" class = "img_a" ><img  src = "img/calender.png" class = "calender_img"></a>
                <img  src = "img/rightButton.png">
            </div>
        </div>
    </section>
    <section class="admin-section2">
        <h1>Welkom, admin</h1>
        <div class = "admin-text">
            <p>Openstaande reserveringen: <?= count($reservations) ?></p>
            <a href = "admin_reservations.php">Ga naar reserveringen</a>
            <p>Gebruikers: <?= count($users) ?></p>
            <a href = "users.php">Ga naar gebruikers</a>
        </div>
        <img id="important-image" src="img/dc6.gif" style="margin-top: 10rem; width: 300px; visibility: hidden; display: none;">
        <script>
        let img = document.getElementById('important-image');
        let amazingBool = false;
        function amazing(){
            if(!amazingBool){
                img.style.visibility = 'visible';
                img.style.display = 'block';
                amazingBool = true;
            } else {
                img.style.visibility = 'hidden';
                img.style.display = 'none';
                amazingBool = false;
            }
        }
        </script>
    </section>

    <section class="admin-section3">
        <h1>Gebruikers</h1>
        <?php for($x = 0; $x < 5; $x++) { ?>
            <div class = "message">
                <div class = "person">
                    <h1> <?= mb_strimwidth($users[$x]['firstName'], 0, 1).'.'.mb_strimwidth($users[$x]['lastName'], 0, 1).'.'; ?></h1>
                </div>
                <div>
                    <h2>Naam gebruiker: <?= $users[$x]['firstName'].' '.$users[$x]['lastName']?></h2>
                    <p>E-mail gebruiker: <?= $users[$x]['email']?></p>
                    <p>Tel gebruiker: <?= $users[$x]['phoneNumber']?></p>
                </div>
            </div>
            <?php } ?>
        <a href = "users.php">Alle gebruikers</a>
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
