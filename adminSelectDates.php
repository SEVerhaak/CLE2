<?php
session_start();
/** @var array $db */
/** @var array $takendates */
require_once 'includes/database.php';
require_once 'includes/functions.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header('location: index.php');
} else if (!isset($_SESSION['user']['admin'])) {
    header('location: index.php');
} else if ($_SESSION['user']['admin'] !== '1') {
    header('location: index.php');
} else {
    $userID = 1;
    $amount_people = 0;
    $timeBegin = strtotime('00:00:00');
    $timeEnd = strtotime('00:00:00');
    $service = 'n.a';
    $extraInfo = 'Datum geblokkeerd';
    $timeBegin = date("h:i:s", $timeBegin);
    $timeEnd = date("h:i:s", $timeEnd);
    $checkMultiple = false;

    $currentTime = time();
    $currentTimeSQL = date("Y-m-d h:i:s", $currentTime);
    $currentTimeHTML = date("Y-m-d", $currentTime);

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['beginDate'])){
            $errors['startDate'] = 'Begindatum moet ingevuld zijn';
        } else{
            $beginDate = $_POST['beginDate'];
        }
        if (isset($_POST['checkMultiple'])){
            $checkMultiple = true;
            if (empty($_POST['endDate'])){
                $errors['endDate'] = 'Einddatum moet ingevuld zijn';
            } else{
                $endDate = $_POST['endDate'];
            }
        }
        //print_r($beginDate);
        //print_r($endDate);
        if($checkMultiple){
            $begin = new DateTime($beginDate);
            $end = new DateTime($endDate);
            $end = $end->modify( '+1 day' );

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $date = $dt->format("Y-m-d");
                $sqlDate = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType, extraInfo) 
        VALUES ('$userID','$amount_people','$date','$timeBegin','$timeEnd','$currentTimeSQL','$service', '$extraInfo')";
                if (mysqli_query($db, $sqlDate)) {
                    //echo "New record created successfully";
                    //header('Location: admin.php');
                    $succes = true;
                } else {
                    echo "Error: " . $sqlDate . "<br>" . mysqli_error($db);
                    $errorMessage = "An error has occurred";
                    $succes = false;
                }
            }
            if ($succes){
                $succesMessage = 'Datums zijn gewijzigd naar niet beschikbaar';
            }
            mysqli_close($db);
        }
        else{
            $sqlDate = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType, extraInfo) 
        VALUES ('$userID','$amount_people','$beginDate','$timeBegin','$timeEnd','$currentTimeSQL','$service', '$extraInfo')";
            if (mysqli_query($db, $sqlDate)) {
                // echo "New record created successfully";
                //header('Location: admin.php');
                $succes = true;
            } else {
                echo "Error: " . $sqlDate . "<br>" . mysqli_error($db);
                $errorMessage = "An error has occurred";
                $succes = false;
            }
            if ($succes){
                $succesMessage = 'Datums zijn gewijzigd naar niet beschikbaar';
            }
            mysqli_close($db);
        }
    }
}

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
            <a href="index.php"><img class="logo" src="img/logo_dk.png"></a>
            <div class="header-links">
                <a class="header-link-text" href="reservation.php">Reserveren</a>
                <a class="header-link-text" href="about.php">Over ons</a>
                <a class="header-link-text" href="news.php">Nieuws</a>
                <a class="header-link-text" href="contact.php">Contact</a>
            </div>
        </div>
        <div class="nav-left">
            <a class="login" href="admin.php">Login</a>
        </div>
    </nav>
</header>
<body>
<h2>Selecteer een datum om de beschikbaarheid ervan aan te passen</h2>
<form action="#" method="post">
    <label for="beginDate">Begin datum:</label>
    <input type="date" id="beginDate" name="beginDate" min="<?= $currentTimeHTML ?>">
    <p class="error"><?php if(isset($errors['startDate'])){
        echo $errors['startDate'];
        } else{
        echo '';
        } ?></p>
    <label for="checkMultiple">Reeks datums niet beschikbaar opgeven</label>
    <input type="checkbox" id="checkMultiple" name="checkMultiple">
    <br>
    <label for="endDate">Eind datum:</label>
    <input type="date" id="endDate" name="endDate" min="<?= $currentTimeHTML ?>" >
    <p class="error"><?php if(isset($errors['endDate'])){
            echo $errors['endDate'];
        } else{
            echo '';
        } ?></p>
    <p class="succes"><?php if(isset($succesMessage)){
            echo $succesMessage;
        } else{
            echo '';
        } ?></p>
    <button type="submit">Opslaan</button>
</form>
</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
<script>
    let checkbox = document.getElementById('checkMultiple');
    let secondDateBox = document.getElementById('endDate');
    secondDateBox.disabled = true;

    checkbox.addEventListener('change', (event) => {
        secondDateBox.disabled = !event.currentTarget.checked;
    })
</script>
</html>

