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
    <link href="css/news.css" rel="stylesheet">
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
<main>
    <h1>Nieuws</h1>
    <div class="block">
        <img src="./img/pic_news.png" alt="Instagram Picture">
        <p>Altijd leuk om een berg vrienden op bezoek te hebben. Kan ik me uitleven op de hapjes! In dit geval Ottolenghi geïnspireerd. Ratatouille met kokos komkommersaus, geroosterde bietjes met limoenbladsalsa, quinoa salade met waterkers, steak van knolselderij met café de Paris saus. Love it! Met oa @stolen7 . #denisekookt #denisekooktvoorvrienden #Ottolenghi #Ottolenghiopjebord #koken #homecooking #homemade #foodphotography #foodie #genieten #homechef</p>
    </div>
    <div class="block">
        <img src="./img/pic_news.png" alt="Instagram Picture">
        <p>Altijd leuk om een berg vrienden op bezoek te hebben. Kan ik me uitleven op de hapjes! In dit geval Ottolenghi geïnspireerd. Ratatouille met kokos komkommersaus, geroosterde bietjes met limoenbladsalsa, quinoa salade met waterkers, steak van knolselderij met café de Paris saus. Love it! Met oa @stolen7 . #denisekookt #denisekooktvoorvrienden #Ottolenghi #Ottolenghiopjebord #koken #homecooking #homemade #foodphotography #foodie #genieten #homechef</p>
    </div>
    <div class="block">
        <img src="./img/pic_news.png" alt="Instagram Picture">
        <p>Altijd leuk om een berg vrienden op bezoek te hebben. Kan ik me uitleven op de hapjes! In dit geval Ottolenghi geïnspireerd. Ratatouille met kokos komkommersaus, geroosterde bietjes met limoenbladsalsa, quinoa salade met waterkers, steak van knolselderij met café de Paris saus. Love it! Met oa @stolen7 . #denisekookt #denisekooktvoorvrienden #Ottolenghi #Ottolenghiopjebord #koken #homecooking #homemade #foodphotography #foodie #genieten #homechef</p>
    </div>
</main>
</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
</html>
