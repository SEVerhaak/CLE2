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
        if (empty($_POST["fName"])) {
            $errors['fName'] = 'Voornaam is vereist';
        } else {
            $fName = mysqli_real_escape_string($db, $_POST['fName']);
        }
        if (empty($_POST["lName"])) {
            $errors['lName'] = 'Achternaam is vereist';
        } else {
            $lName = mysqli_real_escape_string($db, $_POST['lName']);
        }
        if (empty($_POST["email"])) {
            $errors['email'] = 'Email is vereist';
        } else {
            $email = mysqli_real_escape_string($db, $_POST['email']);
        }
        if (empty($_POST["phone"])) {
            $errors['phone'] = 'Telefoonnummer is vereist';
        } else {
            $phone = mysqli_real_escape_string($db, $_POST['phone']);
        }
        if (empty($_POST["password"])) {
            $errors['password'] = 'Wachtwoord is vereist';
        } else {
            $password = mysqli_real_escape_string($db, $_POST['password']);
        }
        if (empty($_POST["passwordRepeat"])) {
            $errors['passwordRepeat'] = 'Wachtwoord is vereist';
        } else {
            $passwordRepeat = mysqli_real_escape_string($db, $_POST['passwordRepeat']);
        }
        $extraInfo = $_POST["extraInfo"];
    }
    // fName lName email phone password passwordRepeat
    if (empty($errors)) {
        if ($password !== $passwordRepeat) {
            $errors['passwordRepeat'] = 'Wachtwoorden komen niet overeen met elkaar!';
        } else {
            // sql statement
            $sqlUser = "INSERT INTO `users`(`firstName`, `lastName`, `email`, `password`, `phoneNumber`,`creationDate`, `isAdmin`) 
                        VALUES ('$fName','$lName','$email','$password','$phone','$currentTimeSQL',0)";
            $userResult = mysqli_query($db, $sqlUser)
            or die('Error '.mysqli_error($db).' with query '.$sqlUser);

            $sqlUserID = "SELECT * FROM users WHERE email LIKE '$email'";
            $result = mysqli_query($db, $sqlUserID)
            or die('Error '.mysqli_error($db).' with query '.$sqlUserID);
            $userData = [];
            while($row = mysqli_fetch_assoc($result))
                $userData[] = $row;
            if (count($userData) === 0){
                //header("Location: index.php");
            }

            $userID = $userData[0]['id'];
            $sqlReservation = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType, extraInfo) 
        VALUES ('$userID','$amount_people','$date','$timeBegin','$timeEnd','$currentTimeSQL','$service', '$extraInfo')";
            if (mysqli_query($db, $sqlReservation)) {
                // echo "New record created successfully";
                header('Location: index.php');
            } else {
                //echo "Error: " . $sql . "<br>" . mysqli_error($db);
                $errorMessage = "An error has occurred";
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
        <p class="dateInvisible"><?= takenDatesCheckerDataFetch($db)[$i] ?></p>
    <?php } ?>
</div>
<div class="reservation-box">
    <div id="section1">
        <h1>Wat wil je boeken</h1>
    <form action="#" method="POST">
        <div class="flex-side">
            <input class="inputSection1" type="radio" name="service" id="catering"
                       value="catering" <?php if (count($errors) > 0 and isset($_POST["service"])) {
                    if ($_POST['service'] == 'catering') {
                        echo 'checked="checked"';
                    }
                } ?>>
                <img class="catering">
                <label for="service1"> Catering </label>
                <input class="inputSection1" type="radio" name="service" id="workshop"
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
                <input class="inputSection1" type="date" name="date" id="date-id" min='<?= $currentTimeHTML ?>'
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
                <input class="amount-value inputSection1" type="number" value="2" name="amount_people" min="2" max="16"
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
            <input class="inputSection1" value="timeslot1" type="radio" name="time"
                       id="time-1" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                if ($_POST['time'] == 'timeslot1') {
                    echo 'checked="checked"';
                }
            } ?>>
        </div>
        <div class="available-time">
            <p>17:00-18:00</p>
            <p>prijs</p>
            <input class="inputSection1" value="timeslot2" type="radio" name="time"
                       id="time-2" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                if ($_POST['time'] == 'timeslot2') {
                    echo 'checked="checked"';
                }
            } ?>>
        </div>
        <div class="available-time">
            <p>18:00-19:00</p>
            <p>prijs</p>
            <input class="inputSection1" value="timeslot3" type="radio" name="time"
                       id="time-3" <?php if (count($errors) > 0 and isset($_POST["time"])) {
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
        <p id="jsError"></p>
            <button id="nextButton" type="button">Volgende stap</button>
    </div>
    <div id="section2">
        <h1>Persoonlijke gegevens</h1>
        <div class="first-name-form">
            <label for="fName">Voornaam</label>
            <input class="inputSection2" id="fName" type="text" name="fName" value='<?php if (count($errors) > 0 and isset($_POST["fName"])) {
                echo $_POST['fName'];
            }
            ?>'>
        </div>
        <p class="error page-2">
            <?php if (isset($errors['fName'])) {
                echo $errors['fName'];
            } else {
                echo '';
            } ?>
        </p>
        <div class="last-name-form">
            <label for="lName">Achternaam</label>
            <input class="inputSection2" id="lName" type="text" name="lName" value='<?php if (count($errors) > 0 and isset($_POST["lName"])) {
                echo $_POST['fName'];
            }
            ?>'>
        </div>
        <p class="error page-2">
            <?php if (isset($errors['lName'])) {
                echo $errors['lName'];
            } else {
                echo '';
            } ?>
        </p>
        <div class="email-form">
            <label for="email">Email</label>
            <input class="inputSection2" id="email" type="email" name="email" value='<?php if (count($errors) > 0 and isset($_POST["email"])) {
                echo $_POST['email'];
            }
            ?>'>
        </div>
        <p class="error page-2">
            <?php if (isset($errors['email'])) {
                echo $errors['email'];
            } else {
                echo '';
            } ?>
        </p>
        <div class="phone-form">
            <label for="phone">Telefoon nummer</label>
            <input class="inputSection2" id="phone" type="number" name="phone" value='<?php if (count($errors) > 0 and isset($_POST["phone"])) {
                echo $_POST['phone'];
            }
            ?>'>
        </div>
        <p class="error page-2">
            <?php if (isset($errors['phone'])) {
                echo $errors['phone'];
            } else {
                echo '';
            } ?>
        </p>
        <div class="password-form">
            <label for="password">Wachtwoord</label>
            <input class="inputSection2" id="password" type="password" name="password" >
        </div>
        <p class="error page-2">
            <?php if (isset($errors['password'])) {
                echo $errors['password'];
            } else {
                echo '';
            } ?>
        </p>
        <div class="password-form">
            <label for="passwordRepeat">Herhaal wachtwoord</label>
            <input class="inputSection2" id="passwordRepeat" type="password" name="passwordRepeat">
        </div>
        <p class="error page-2">
            <?php if (isset($errors['passwordRepeat'])) {
                echo $errors['passwordRepeat'];
            } else {
                echo '';
            } ?>
        </p>
        <div class="extra-info-form">
            <label for="extraInfo">Herhaal wachtwoord</label>
            <input class="extraInfo" id="extraInfo" type="text" name="extraInfo">
        </div>

        <button id="prevButton" type="button">Vorige stap</button>
        <button id="submit-button" type="submit">Reserveren</button>

    </div>
    </form>
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
<script>
    init();

    let nextButton = document.getElementById("nextButton");
    let prevButton = document.getElementById("prevButton");

    let section1 = document.getElementById("section1");
    let section2 = document.getElementById("section2");

    let inputElements = document.getElementsByClassName("inputSection1");

    nextButton.addEventListener("click", checkInput);
    prevButton.addEventListener("click", prevPage);

    function init(){
        let errorElementsPage2 = document.getElementsByClassName("error page-2")
        let errorBool = false;
        for (let i = 0; i <errorElementsPage2.length; i++) {
            if (errorElementsPage2[i].innerHTML.includes("vereist")){
                errorBool = true;
            }
        }
        if (errorBool){
            window.addEventListener('load', function () {
                nextPage();
            })
        } else{
            window.addEventListener('load', function () {
                prevPage();
            })
        }
    }

    function checkInput() {
        let amountChecked = 0;
        let emptyFields = 0;
        for (let i = 0; i < inputElements.length; i++) {
            // console.log(inputElements[i].checked)
            if (inputElements[i].checked) {
                amountChecked += 1;
            }
            if (inputElements[i].value == '') {
                emptyFields += 1;
            }
        }
        //console.log(amountChecked);
        //console.log(emptyFields);
        if (amountChecked === 2 && emptyFields === 0) {
            nextPage();
            document.getElementById("jsError").innerHTML = '';
        } else {
            document.getElementById("jsError").innerHTML = 'nog niet alle vereiste velden zijn ingevuld';
        }
    }

    function nextPage(){
        section1.style.visibility = 'hidden';
        section1.style.display = 'none';

        section2.style.visibility = 'visible';
        section2.style.display = 'block';
    }

    function prevPage() {
        section1.style.visibility = 'visible';
        section1.style.display = 'block';

        section2.style.visibility = 'hidden';
        section2.style.display = 'none';

    }

</script>
<script>
    let dateElement = document.getElementById("date-id");
    let taken = false;
    dateElement.addEventListener("input", function () {
        taken = false;
        for (let i = 0; i < document.getElementsByClassName("dateInvisible").length; i++) {
            if (document.getElementById('date-id').value === document.getElementsByClassName("dateInvisible")[i].innerHTML) {
                taken = true;
            } else {
            }
        }
        if (taken) {
            document.getElementById("nextButton").disabled = true;
            document.getElementById("date-error").innerHTML = 'deze datum is niet meer beschikbaar'
        } else {
            document.getElementById("nextButton").disabled = false;
            document.getElementById("date-error").innerHTML = ''

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