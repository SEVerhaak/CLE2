<?php
// required when working with sessions
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);



/** @var mysqli $db */
require_once 'includes/database.php';
//require_once 'adminmail.php';
// require 'includes/functions.php';

//adminCheck();

?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">

    <title>Log in</title>
</head>
<!-- Header -->
<header>
    <nav>
        <div class="nav-right">
            <a href="index.php"><img class="logo" src="img/logo_dk.png"></a>
            <div class="header-links">
                <a class="header-link-text" href="reservation.php">Reserveren</a>
                <a class="header-link-text" href="about.php">Over ons</a>
                <a class="header-link-text" href="news.php">Nieuws</a>
                <a class="header-link-text" href="contact.php">Contact</a>
            </div>
        </div>
        <div class="nav-left">
            <?php if (!isset($_SESSION['user'])) { ?>
                <a class="login" href="login.php">Login</a>
            <?php } else { ?>
                <a class="login" href="logout.php">Log uit</a>
                <a class="login" href="user-reservations.php">Mijn reserveringen</a>
            <?php } ?>

        </div>
    </nav>
</header>
<body>
<?php if (isset($_SESSION['user']['admin'])) { ?>
    <div class="sidebar">
        <a href="admin.php"><img src="img/home.png"></a>
        <a href="users.php"><img src="img/users.png"></a>
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>


<!-- form -->
<div class="center-box">


    <div class="login-container">
        <h1 class="title">Email sucesvol verzonden!</h1>
        <a href="admin.php">Terug</a>
    </div>
</div>

</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <a href="https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>




