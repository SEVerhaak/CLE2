<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <title>Denise Kookt!</title>
</head>
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
        <div class="navbar">
            <div class="containers nav-container">
                <input class="checkbox" type="checkbox" name="" id="" />
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <div class="logo">
                    <a href = "index.php"><img class="logo" src="img/logo_dk.png"></a>
                </div>
                <div class="menu-items">
                    <li><a class="header-link-text" href="reservation.php">Reserveren</a></li>
                    <li><a class="header-link-text" href="about.php">Over ons</a></li>
                    <li><a class="header-link-text" href="news.php">Nieuws</a></li>
                    <li><a class="header-link-text" href="contact.php">Contact</a></li>
                    <br>
                    <br>
                    <div class="nav-left-mobile">
                        <?php if(!isset($_SESSION['user'])){?>
                            <a class="login-mobile" href="login.php">Login</a>
                        <?php }else{ ?>
                            <a class="login-mobile" href = "logout.php">Log uit</a>
                            <a class="login-mobile" href = "user-reservations.php">Mijn reserveringen</a>
                        <?php } ?>
                    </div>
                </div>
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
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>
<!-- Indexinformatie -->
<main>
    <div class="block">
        <h1 class="top">Welkom bij</h1>
       <h1>Denise Kookt!</h1>
    <div class="links">
    <a href="reservation.php" class="indexLink">Reserveren</a>
    <a href="about.php" class="indexLink">Meer infomatie</a>
    </div>
    </div>
</main>
</body>
<footer>
        <div class="footer-style">
            <img class="logo-mobile" src="img/logo_dk.png">
            <p class="footer-main-text">Denise Kookt!</p>
            <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
        </div>
    </footer>
</html>
