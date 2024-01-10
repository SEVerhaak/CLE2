<?php

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Denise Kookt</title>
</head>
<header>
    <nav>
        <div class="nav-right">
            <img class="logo" src="">
            <a class="header-link-text" href="#">Reserveren</a>
            <a class="header-link-text" href="#">Over ons</a>
            <a class="header-link-text" href="#">Nieuws</a>
            <a class="header-link-text" href="#">Contact</a>
        </div>
        <div class="nav-left">
            <a class="login">Login</a>
        </div>
    </nav>
</header>
<body>
<div class="reservation-box">
    <h1>Wat wil je boeken</h1>
    <form action="#" method="POST">
        <div class="flex-side">
            <input type="radio" name="service" id="catering">
            <img class="catering">
            <label for="service1"> Catering </label>
            <input type="radio" name="service" id="workshop">
            <img class="workshop">
            <label for="service2"> Workshop </label>
        </div>
        <div class="flex-down">
            <label for="date">Voor welke datum?</label>
            <input type="date" name="date" id="date">
        </div>
        <div class="flex-down">
            <label for="amount_people">Amount of people</label>
            <input type="number" name="amount_people" min="2" max="16">
        </div>
        <div class="available-time">
            <p>16:00-17:00</p>
            <p>prijs</p>
            <input type="radio" name="time" id="time">
        </div>
        <div class="available-time">
            <p>17:00-18:00</p>
            <p>prijs</p>
            <input type="radio" name="time" id="time">
        </div>
        <div class="available-time">
            <p>18:00-19:00</p>
            <p>prijs</p>
            <input type="radio" name="time" id="time">
        </div>
        <button type="submit">Reserveren!</button>
    </form>
</div>
</body>
<footer>
    <img class="logo" src="">
    <p class="footer-main-text">Denise Kookt</p>
    <div class="socials">
        <p class="footer-social-text">Socials</p>
        <img class="social-img" src="">
        <img class="social-img" src="">
    </div>
</footer>
</html>
