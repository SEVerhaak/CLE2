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
        <a href="settings.php"><img src="img/settings.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
    </div>
<?php } ?>
<!-- Nieuwsberichten -->
<main>
    <h1>Nieuws</h1>
    <div class="block">
        <img src="./img/pic_news.png" alt="Instagram Picture 1" class="instaimg">
        <p class="instatext">Altijd leuk om een berg vrienden op bezoek te hebben. Kan ik me uitleven op de hapjes! In dit geval Ottolenghi geïnspireerd. Ratatouille met kokos komkommersaus, geroosterde bietjes met limoenbladsalsa, quinoa salade met waterkers, steak van knolselderij met café de Paris saus. Love it! Met oa @stolen7. #denisekookt #denisekooktvoorvrienden #Ottolenghi #Ottolenghiopjebord #koken #homecooking #homemade #foodphotography #foodie #genieten #homechef</p>
    </div>
    <div class="block">
        <img src="./img/denisekookt_ipcia.png" alt="Instagram Picture 3" class="instaimg">
        <p class="instatext">Zo geïnspireerd door @louisebylisapalandet en haar mooie kookboek. Bezoek vandaag verwend met gegrilde courgette met pecannoten, bietjes met sesamzaadjes, gegrilde kip, zadencrackers met crème fraiche, dijonmosterd en parmezaan. En toe een deens appeltaartje met abrikozen en vanilleroom. Met appeltjes uit eigen tuin #denisekookt #deenskoken #homecooking #homemade #homechef #foodie #foodphotography #foodieopreis #moestuin #appeltjes</p>
    </div>
    <div class="block">
        <img src="./img/foodpic2.png" alt="Instagram Picture 2" class="instaimg">
        <p class="instatext">Gewoon omdat het woensdag is. Eend, pastinaak, geroosterde pompoen en salade van waterkers. Erbij een heerlijke rode Primitivo! #denisekookt #koken #homecooking #homemade #bijnaherfst #wijnspijs #wijntjeerbij #foodphotography #foodie</p>
    </div>
    <div class="spacer">

    </div>
</main>
</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>
