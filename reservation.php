<?php
//session_start();
/** @var array $db */
/** @var array $takendates */

// tijdelijke error reporting opties
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// includes
require_once 'includes/database.php';
require_once 'includes/functions.php';

// tijdelijke userID waarde
$userID = 100;
// huidige tijd
$currentTime = time();
//huidige tijd met SQL formatting
$currentTimeSQL = date("Y-m-d h:i:s", $currentTime);
$currentTimeHTML = date("Y-m-d", $currentTime + 259200);

// tijdsloten variable, eerste array is begin tijd en tweede array is eind tijd
$timeSlots = array(
    array('17:00:00', '18:00:00', '19:00:00'),
    array('18:00:00', '19:00:00', '20:00:00')
);

//takenDatesCheckerDataFetch($db);
print_r(takenDatesCheckerDataFetch($db));


// error array
$errors = [];
// Na POST voer de volgende code uit:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["service"])) {
        $errors['service'] = 'Dienst is vereist';
    } else {
        $service = mysqli_real_escape_string($db, $_POST['service']);
    }
    if (empty($_POST["date"])) {
        $errors['date'] = 'Datum is vereist';
    } else {
        $date = mysqli_real_escape_string($db, $_POST['date']);
    }
    if (empty($_POST["amount_people"])) {
        $errors['amount_people'] = 'Datum is vereist';
    } else {
        $amount_people = mysqli_real_escape_string($db, $_POST['amount_people']);
    }
    if (empty($_POST["time"])) {
        $errors['time'] = 'Datum is vereist';
    } else {
        $time = mysqli_real_escape_string($db, $_POST['time']);
        // timeslot omzetten naar daadwerkelijke tijd
        if ($time == 'timeslot1') {
            $timeBegin = $timeSlots[0][0];
            $timeEnd = $timeSlots[1][0];
        } else if ($time == 'timeslot2') {
            $timeBegin = $timeSlots[0][1];
            $timeEnd = $timeSlots[1][1];
        } else if ($time == 'timeslot3') {
            $timeBegin = $timeSlots[0][2];
            $timeEnd = $timeSlots[1][2];
        } else {
            $errors['time'] = 'Datum is vekeerd';
        }
    }


    if (empty($errors)) {

        // sql statement
        $sql = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType) 
        VALUES ('$userID','$amount_people','$date','$timeBegin','$timeEnd','$currentTimeSQL','$service')";
        if (mysqli_query($db, $sql)) {
            echo "New record created successfully";
            header('Location: index.php');
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($db);
            // header('Location: create.php');
            $errorMessage = "An error has occurred";
        }
        mysqli_close($db);
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
    <link rel="stylesheet" href="css/style.css">
    <title>Denise Kookt</title>
</head>
<header>
    <nav>
        <div class="nav-right">
            <img class="logo" src="img/logo_dk.png">
            <a class="header-link-text" href="#">Reserveren</a>
            <a class="header-link-text" href="#">Over ons</a>
            <a class="header-link-text" href="#">Nieuws</a>
            <a class="header-link-text" href="#">Contact</a>
        </div>
        <div class="nav-left">
            <a class="login" href="admin.php">Login</a>
        </div>
    </nav>
</header>
<div class="hidden-meta-data" style="display: none;">
    <?php for ($i = 0; $i < count(takenDatesCheckerDataFetch($db)); $i++) { ?>
        <p class=<?= "dateInvisible" ?>><?= takenDatesCheckerDataFetch($db)[$i] ?></p>
    <?php } ?>
</div>
<div>
    <div class="reservation-box">
        <h1>Wat wil je boeken</h1>
        <form action="#" method="POST">
            <div class="flex-side">
                <input type="radio" name="service" id="catering"
                       value="catering" <?php if (count($errors) > 0 and isset($_POST["service"])) {
                    if ($_POST['service'] == 'catering') {
                        echo 'checked="checked"';
                    }
                } ?>>
                <img class="catering">
                <label for="service1"> Catering </label>
                <input type="radio" name="service" id="workshop"
                       value="workshop" <?php if (count($errors) > 0 and isset($_POST["service"])) {
                    if ($_POST['service'] == 'workshop') {
                        echo 'checked="checked"';
                    }
                } ?>>
                <img class="workshop">
                <label for="service2"> Workshop </label>
            </div>
            <p class="error">
                <?php if (isset($errors['service'])) {
                    echo $errors['service'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="flex-down">
                <label for="date">Voor welke datum?</label>
                <input type="date" name="date" id="date-id" min='<?= $currentTimeHTML ?>'
                       value='<?php if (count($errors) > 0 and isset($_POST["date"])) {
                           echo $_POST['date'];
                       }
                       ?>'>
            </div>
            <p class="error" id="date-error">
                <?php if (isset($errors['date'])) {
                    echo $errors['date'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="flex-side">
                <label for="amount_people">Amount of people</label>
                <button type="button" class="left-button" id="left-button-id">-</button>
                <input class="amount-value" type="number" value="2" name="amount_people" min="2" max="16"
                       readonly="readonly">
                <button type="button" class="right-button" id="right-button-id">+</button>
            </div>
            <p class="error">
                <?php if (isset($errors['amount_people'])) {
                    echo $errors['amount_people'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="available-time">
                <p>16:00-17:00</p>
                <p>prijs</p>
                <input value="timeslot1" type="radio" name="time"
                       id="time" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                    if ($_POST['time'] == 'timeslot1') {
                        echo 'checked="checked"';
                    }
                } ?>>
            </div>
            <div class="available-time">
                <p>17:00-18:00</p>
                <p>prijs</p>
                <input value="timeslot2" type="radio" name="time"
                       id="time" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                    if ($_POST['time'] == 'timeslot2') {
                        echo 'checked="checked"';
                    }
                } ?>>
            </div>
            <div class="available-time">
                <p>18:00-19:00</p>
                <p>prijs</p>
                <input value="timeslot3" type="radio" name="time"
                       id="time" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                    if ($_POST['time'] == 'timeslot3') {
                        echo 'checked="checked"';
                    }
                } ?>>
            </div>
            <p class="error">
                <?php if (isset($errors['time'])) {
                    echo $errors['time'];
                } else {
                    echo '';
                } ?>
            </p>
            <button type="submit">Reserveren!</button>
        </form>
    </div>
</div>
</body>


<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
<script>
    let dateElement = document.getElementById("date-id");
    let taken = false;
    dateElement.addEventListener("input", function () {
        taken = false;
        for (let i = 0; i < document.getElementsByClassName("dateInvisible").length; i++) {
            console.log(document.getElementById('date-id').value)
            if (document.getElementById('date-id').value === document.getElementsByClassName("dateInvisible")[i].innerHTML) {
                document.getElementById("date-error").innerHTML = 'deze datum is niet meer beschikbaar'
                taken = true;
            } else {
                document.getElementById("date-error").innerHTML = ''
            }
        }
        console.log(taken);
    });
</script>
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
        if (value <= 2) {

        } else {
            element.value = value - 1;
        }

    }

</script>
</html>
<div class="socials">

    <img class="social-img" src="">
    <img class="social-img" src="">
</div>