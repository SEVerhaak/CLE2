<?php
session_start();
/** @var array $db */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once 'includes/database.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["service"])) {
        $errors['service'] = 'Dienst is vereist';
    } else {
        $brand = mysqli_real_escape_string($db, $_POST['service']);
    }
    if (empty($_POST["date"])) {
        $errors['date'] = 'Datum is vereist';
    } else {
        $brand = mysqli_real_escape_string($db, $_POST['date']);
    }

   // echo($errors['service']);
}
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
            <img class="logo" src="img/logo_dk.png">
            <a class="header-link-text" href="#">Reserveren</a>
            <a class="header-link-text" href="#">Over ons</a>
            <a class="header-link-text" href="#">Nieuwshghghghghg</a>
            <a class="header-link-text" href="#">Contact</a>
        </div>
        <div class="nav-left">
            <a class="login" href = "#">Login</a>
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
        <div class="flex-side">
            <label for="amount_people">Amount of people</label>
            <button type="button" class="left-button" id="left-button-id">-</button>
            <input class="amount-value" type="number" value="2" name="amount_people" min="2" max="16" readonly="readonly">
            <button type="button" class="right-button" id="right-button-id">+</button>
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
    <div class = "footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
<script>
    let element = document.getElementsByClassName('amount-value')[0];
    let leftButton = document.getElementsByClassName('left-button')[0];
    let rightButton = document.getElementsByClassName('right-button')[0];
    leftButton.addEventListener("click", decrease);
    rightButton.addEventListener("click", increase);

    function increase() {
        let value = parseInt(element.value)
        if (value >= 16) {
       } else {
            element.value = value + 1;
        }
    }
    function decrease() {
        let value = parseInt(element.value)
        if (value <= 2){

        } else{
            element.value = value - 1;
        }


    }

</script>
</html>
<div class="socials">

    <img class="social-img" src="">
    <img class="social-img" src="">
</div>