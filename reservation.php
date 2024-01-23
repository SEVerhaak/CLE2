<?php
session_start();
/** @var array $db */
/** @var array $takendates */
/** @var string $emailUser */


if (isset($_SESSION['user'])){
    header('location: reservationUser.php');
}

// tijdelijke error reporting opties
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/
// includes
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'mail.php';

$sqlUserID = "SELECT * FROM settings WHERE 1";
$result = mysqli_query($db, $sqlUserID)
or die('Error ' . mysqli_error($db) . ' with query ' . $sqlUserID);
$settings = [];
while ($row = mysqli_fetch_assoc($result))
    $settings[] = $row;
if (count($settings) === 0) {
    header("Location: index.php");
} else {
// huidige tijd
    $currentTime = time();
//huidige tijd met SQL&HTML formatting
    $currentTimeSQL = date("Y-m-d h:i:s", $currentTime);
    $currentTimeHTML = date("Y-m-d", $currentTime + 259200);

// tijdsloten variable, eerste array is begin tijd en tweede array is eind tijd
    $timeSlots = array(
        array($settings[0]['timeSlotBegin1'], $settings[0]['timeSlotEnd1']),
        array($settings[0]['timeSlotBegin2'], $settings[0]['timeSlotEnd2'])
    );

// error array
    $errors = [];
// Na POST check of alle input ingevuld is
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
            // extra check om te kijken of er geen verkeerde waarde ingevuld is voor de max en min aantal gasten.
            $amount_people = mysqli_real_escape_string($db, $_POST['amount_people']);
            if ($amount_people > $settings[0]["maxGuest"] or $amount_people < $settings[0]["minGuest"]) {
                $errors = 'Maximaal aantal gasten overschreden';
            }
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
            } else {
                $errors['time'] = 'Datum is onjuist ingevoerd';
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
                //check of email al in gebruik is
                $sqlEmailCheck = "SELECT * FROM users WHERE email LIKE '$email'";
                $sqlEmailCheckResult = mysqli_query($db, $sqlEmailCheck)
                or die('Error ' . mysqli_error($db) . ' with query ' . $sqlEmailCheck);
                $emailMatch = [];
                while ($row = mysqli_fetch_assoc($sqlEmailCheckResult))
                    $emailMatch[] = $row;
                if (count($emailMatch) > 0) {
                    $errors['email'] = 'E-Mail adres is al in gebruik of niet geldig';
                }
            }
            if (empty($_POST["phone"])) {
                $errors['phone'] = 'Telefoonnummer is vereist';
            } else {
                $phone = mysqli_real_escape_string($db, $_POST['phone']);
                if (strlen(strval($phone)) > 8 and strlen(strval($phone)) < 11) {

                } else {
                    $errors['phone'] = 'Voer een geldig telefoonnummer in';
                }
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
        // als er geen errors zijn voer query uit

        if (empty($errors)) {
            // check of wachtwoorden overeen komen met elkaar
            if ($password !== $passwordRepeat) {
                $errors['passwordRepeat'] = 'Wachtwoorden komen niet overeen met elkaar!';
            } else {
                // sql statement voor toevoegen van gebruiker
                $password = password_hash($password, PASSWORD_DEFAULT);
                $sqlUser = "INSERT INTO `users`(`firstName`, `lastName`, `email`, `password`, `phoneNumber`,`creationDate`, `isAdmin`) 
                        VALUES ('$fName','$lName','$email','$password','$phone','$currentTimeSQL',0)";
                $userResult = mysqli_query($db, $sqlUser)
                or die('Error ' . mysqli_error($db) . ' with query ' . $sqlUser);
                // sql statement voor ophalen user ID gebaseerd op uniek email adres
                $sqlUserID = "SELECT * FROM users WHERE email LIKE '$email'";
                $result = mysqli_query($db, $sqlUserID)
                or die('Error ' . mysqli_error($db) . ' with query ' . $sqlUserID);
                $userData = [];
                while ($row = mysqli_fetch_assoc($result))
                    $userData[] = $row;
                if (count($userData) === 0) {
                    //header("Location: index.php");
                }
                // sla nieuw aangemaakt user id op
                $userID = $userData[0]['id'];
                // sql statement voor toevoegen van reservering gekoppeld aan user id
                $sqlReservation = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType, extraInfo) 
        VALUES ('$userID','$amount_people','$date','$timeBegin','$timeEnd','$currentTimeSQL','$service', '$extraInfo')";
                if (mysqli_query($db, $sqlReservation)) {
                    // echo "New record created successfully";
                    $_SESSION['user'] = [
                        'id' => $userID];

                    sendEmail($email, $amount_people, $service, $timeBegin, $timeEnd, $fName, $lName, $extraInfo, $date);
                    // $amountPeople $service, $reservationBeginTime, $reservationEndTime, $fName, $lName, $extraInfo
                    header('Location: user-reservations.php');

                } else {
                    //echo "Error: " . $sql . "<br>" . mysqli_error($db);
                    $errorMessage = "An error has occurred";
                }
                mysqli_close($db);
            }

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
            <a href = "index.php"><img class="logo" src="img/logo_dk.png"></a>
            <div class = "header-links">
                <a class="selected-user header-link-text" href="reservation.php" >Reserveren</a>
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
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>
<div class="hidden-meta-data" style="display: none;">
    <?php for ($i = 0;
               $i < count(takenDatesCheckerDataFetch($db));
               $i++) { ?>
        <p class="dateInvisible"><?= takenDatesCheckerDataFetch($db)[$i] ?></p>
    <?php } ?>
</div>
<div class="reservation-box">
    <div id="section1" class="section1">
        <h1>Wat wil je reserveren?</h1>
        <form action="#" method="POST">
            <div class="flex-side">
                <div class="catering-box">

                    <div class="catering-radio">
                        <label>
                            <input class="inputSection1" type="radio" name="service" id="catering"
                                   value="catering" <?php if (count($errors) > 0 and isset($_POST["service"])) {
                                if ($_POST['service'] == 'catering') {
                                    echo 'checked="checked"';
                                }
                            } ?>>
                            <img class="catering" src="img/icon1.png">
                        </label>
                        <p>Catering</p>
                    </div>
                </div>
                <div class="workshop-box">

                    <div class="workshop-radio">
                        <label>
                            <input class="inputSection1" type="radio" name="service" id="workshop"
                                   value="workshop" <?php if (count($errors) > 0 and isset($_POST["service"])) {
                                if ($_POST['service'] == 'workshop') {
                                    echo 'checked="checked"';
                                }
                            } ?> >
                            <img class="workshop" src="img/icon2.png">
                        </label>
                        <p>Workshop</p>
                    </div>
                </div>
            </div>
            <p class="error">
                <?php if (isset($errors['service'])) {
                    echo $errors['service'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="flex-down" id="firstHidden">
                <label for="date">Voor welke datum?</label>
                <input class="inputSection1" type="date" name="date" id="date-id" min='<?= $currentTimeHTML ?>'
                       value='<?php if (count($errors) > 0 and isset($_POST["date"])) {
                           echo htmlentities($_POST['date']);
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

            <div id="hidden-info">
            <label>
                <input class="inputSection1" value="timeslot1" type="radio" name="time"
                       id="time-1" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                    if ($_POST['time'] == 'timeslot1') {
                        echo 'checked="checked"';
                    }
                } ?>>
                <div class="available-time">
                    <p class="time-text"><?php echo date('G:i', strtotime($timeSlots[0][0])) ?>
                        - <?php echo date('G:i', strtotime($timeSlots[0][1])) ?></p>
                    <p class="price time-text"></p>
                </div>
            </label>
            <label>
                <input class="inputSection1" value="timeslot2" type="radio" name="time"
                       id="time-2" <?php if (count($errors) > 0 and isset($_POST["time"])) {
                    if ($_POST['time'] == 'timeslot2') {
                        echo 'checked="checked"';
                    }
                } ?>>
                <div class="available-time">
                    <p class="time-text"><?php echo date('G:i', strtotime($timeSlots[1][0])) ?>
                        - <?php echo date('G:i', strtotime($timeSlots[1][1])) ?></p>
                    <p class="price time-text"></p>
                </div>
                <div class="flex-people">
                    <label for="amount_people">Hoe veel personen?</label>
                    <div>
                        <button type="button" class="left-button" id="left-button-id">-</button>
                        <input class="amount-value inputSection1" id="amount_people" type="number"
                               value="<?= $settings[0]['minGuest'] ?>" name="amount_people" readonly="readonly">
                        <button type="button" class="right-button" id="right-button-id">+</button>
                    </div>
                </div>

                <p class="error">
                    <?php if (isset($errors['amount_people'])) {
                        echo $errors['amount_people'];
                    } else {
                        echo '';
                    } ?>
                </p>
            </label>
            </div>
            <p id="jsError"></p>
            <div class="button-right">
                <button id="nextButton" class="nextButton" type="button">Volgende stap</button>
            </div>
    </div>
    <div id="section2" class="section2">

        <h1>Persoonlijke gegevens</h1>
        <div class="first-name-form">
            <label for="fName">Voornaam</label>
            <input class="inputSection2" id="fName" type="text" name="fName"
                   value='<?php if (count($errors) > 0 and isset($_POST["fName"])) {
                       echo htmlentities($_POST['fName']);
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
            <input class="inputSection2" id="lName" type="text" name="lName"
                   value='<?php if (count($errors) > 0 and isset($_POST["lName"])) {
                       echo htmlentities($_POST['lName']);
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
            <input class="inputSection2" id="email" type="email" name="email"
                   value='<?php if (count($errors) > 0 and isset($_POST["email"])) {
                       echo htmlentities($_POST['email']);
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
            <label for="phone">Telefoonnummer</label>
            <input class="inputSection2" id="phone" type="number" name="phone"
                   value='<?php if (count($errors) > 0 and isset($_POST["phone"])) {
                       echo htmlentities($_POST['phone']);
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
            <input class="inputSection2" id="password" type="password" name="password">
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
            <label for="extraInfo">Zijn er nog bijzonderheden?</label>
            <textarea class="extraInfo" id="extraInfo" type="text" name="extraInfo" rows="10"><?php if (count($errors) > 0 and isset($_POST["extraInfo"])) {
                    echo htmlentities($_POST['extraInfo']);
                }
                ?></textarea>
        </div>
        <div id="button-spacer">
            <button id="prevButton" type="button">Vorige stap</button>
            <button id="submit-button" type="submit">Reserveren</button>
        </div>
    </div>
    </form>
</div>
</body>

<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>
<script>
    document.getElementById("date-id").addEventListener('focus', function (event) {
        event.target.showPicker();
    });

</script>
<script>

    let leftButtonPrice = document.getElementsByClassName('left-button')[0];
    let rightButtonPrice = document.getElementsByClassName('right-button')[0];

    let price = parseInt("<?= $settings[0]['price'] ?>")
    let amountElement = document.getElementById('amount_people')
    let timeSlot = document.getElementsByClassName('price')
    calcPrice();

    function calcPrice() {
        for (let i = 0; i < timeSlot.length; i++) {
            timeSlot[i].innerHTML = price * parseInt(amountElement.value);
            timeSlot[i].innerHTML = "€" + timeSlot[i].innerHTML;
        }
    }
</script>
<script>
    init();

    let nextButton = document.getElementById("nextButton");
    let prevButton = document.getElementById("prevButton");

    let section1 = document.getElementById("section1");
    let section2 = document.getElementById("section2");

    let inputElements = document.getElementsByClassName("inputSection1");

    nextButton.addEventListener("click", checkInput);
    prevButton.addEventListener("click", prevPage);

    function init() {
        let errorElementsPage2 = document.getElementsByClassName("error page-2")
        let errorBool = false;
        for (let i = 0; i < errorElementsPage2.length; i++) {
            if (errorElementsPage2[i].innerHTML.includes("vereist") || errorElementsPage2[i].innerHTML.includes("geldig")) {
                errorBool = true;
            }
        }
        console.log('errors:' + errorBool)
        if (errorBool) {
            window.addEventListener('load', function () {
                nextPage();
            })
        } else {
            window.addEventListener('load', function () {
                prevPage();
            })
        }
    }

    function checkInput() {
        let amountChecked = 0;
        let emptyFields = 0;
        for (let i = 0; i < inputElements.length; i++) {
            if (inputElements[i].checked) {
                amountChecked += 1;
            }
            if (inputElements[i].value == '') {
                emptyFields += 1;
            }
        }
        if (amountChecked === 2 && emptyFields === 0) {
            nextPage();
            document.getElementById("jsError").innerHTML = '';
        } else {
            document.getElementById("jsError").innerHTML = 'nog niet alle vereiste velden zijn ingevuld';
        }
    }

    function nextPage() {
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
    let firstHiddenElement =  document.getElementById('firstHidden')
    let choiceWorksop = document.getElementById('workshop')
    let choiceCatering = document.getElementById('catering')

    firstHiddenElement.style.display = 'none';
    firstHiddenElement.style.visibility = 'hidden';
    choiceWorksop.addEventListener("click", function () {
        firstHiddenElement.style.display = 'flex';
        firstHiddenElement.style.visibility = 'visible';
    })
    choiceCatering.addEventListener("click", function () {
        firstHiddenElement.style.display = 'flex';
        firstHiddenElement.style.visibility = 'visible';
    })



    let hiddenElement = document.getElementById('hidden-info');
    hiddenElement.style.display = 'none';
    hiddenElement.style.visibility = 'hidden';

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
            hiddenElement.style.display = 'none';
            hiddenElement.style.visibility = 'hidden';
            document.getElementById("nextButton").disabled = true;
            document.getElementById("date-error").innerHTML = 'deze datum is niet meer beschikbaar'
        } else {
            hiddenElement.style.display = 'block';
            hiddenElement.style.visibility = 'visible';
            document.getElementById("nextButton").disabled = false;
            document.getElementById("date-error").innerHTML = ''

        }
    });
</script>
<script>
    let element = document.getElementsByClassName('amount-value')[0];
    let currentElementValue = element.value
    let maxValue = parseInt("<?php echo $settings[0]['maxGuest'] ?>")
    let minValue = parseInt("<?php echo $settings[0]['minGuest'] ?>")
    let leftButton = document.getElementsByClassName('left-button')[0];
    let rightButton = document.getElementsByClassName('right-button')[0];
    leftButton.addEventListener("click", decrease);
    rightButton.addEventListener("click", increase);


    function increase() {
        currentElementValue = parseInt(element.value)
        if (currentElementValue >= maxValue) {
        } else {
            element.value = currentElementValue + 1
            calcPrice();
        }
    }

    function decrease() {
        currentElementValue = parseInt(element.value)
        if (currentElementValue <= minValue) {

        } else {
            element.value = currentElementValue - 1
            calcPrice();
        }
    }

</script>
</html>
<div class="socials">

    <img class="social-img" src="">
    <img class="social-img" src="">
</div>