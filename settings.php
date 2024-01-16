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

$errors = [];

$sqlGetSettings = 'SELECT * FROM `settings`';
$result = mysqli_query($db, $sqlGetSettings)
or die('Error ' . mysqli_error($db) . ' with query ' . $sqlGetSettings);
$settings = [];
while ($row = mysqli_fetch_assoc($result))
    $settings[] = $row;
if (count($settings) === 0) {
    //header("Location: index.php");
    $error = 'could not load settings';
}
//print_r($settings);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['price'])) {
        $errors['price'] = 'Prijs mag niet leeg zijn';
    } else {
        $price = mysqli_real_escape_string($db, $_POST['price']);
    }
    if (empty($_POST['timeslotB1'])) {
        $errors['timeslotB1'] = 'Tijdslot begin 1 mag niet leeg zijn';
    } else {
        $timeslotB1 = mysqli_real_escape_string($db, $_POST['timeslotB1']);
    }
    if (empty($_POST['timeslotE1'])) {
        $errors['timeslotE1'] = 'Tijdslot eind 1 mag niet leeg zijn';
    } else {
        $timeslotE1 = mysqli_real_escape_string($db, $_POST['timeslotE1']);
    }
    if (empty($_POST['timeslotB2'])) {
        $errors['timeslotB2'] = 'Tijdslot begin 2 mag niet leeg zijn';
    } else {
        $timeslotB2 = mysqli_real_escape_string($db, $_POST['timeslotB2']);
    }
    if (empty($_POST['timeslotE2'])) {
        $errors['timeslotE2'] = 'Tijdslot eind 2 mag niet leeg zijn';
    } else {
        $timeslotE2 = mysqli_real_escape_string($db, $_POST['timeslotE2']);
    }
    if (empty($_POST['minGuest'])) {
        $errors['minGuest'] = 'Minimaal aantal gasten mag niet leeg zijn';
    } else {
        $minGuest = mysqli_real_escape_string($db, $_POST['minGuest']);
    }
    if (empty($_POST['maxGuest'])) {
        $errors['maxGuest'] = 'Maximaal aantal gasten mag niet leeg zijn';
    } else {
        $maxGuest = mysqli_real_escape_string($db, $_POST['maxGuest']);
    }
    if (empty($errors)) {
        $settingQuery = "UPDATE `settings` SET 
                      `price`='$price',
                      `timeSlotBegin1`='$timeslotB1',
                      `timeSlotEnd1`='$timeslotE1',
                      `timeSlotBegin2`='$timeslotB2',
                      `timeSlotEnd2`='$timeslotE2',
                      `minGuest`='$minGuest',
                      `maxGuest`='$maxGuest'
                        WHERE 1";

        if (mysqli_query($db, $settingQuery)) {
            // echo "New record created successfully";
            $succes = "Instellingen zijn succesvol gewijzigd";
            header('Location: settings.php');
        } else {
            echo "Error: " . $settingQuery . "<br>" . mysqli_error($db);
            $succes = "Fout bij het wijzigen van instellingen, neem contact op met een systeembeheerder";
        }
    }
    //print_r($errors);
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
    <title>Denise Kookt!</title>
</head>
<header>
    <nav>
        <div class="nav-right">
            <img class="logo" src="img/logo_dk.png">
            <a class="header-link-text" href="reservation.php">Reserveren</a>
            <a class="header-link-text" href="about.php">Over ons</a>
            <a class="header-link-text" href="news.php">Nieuws</a>
            <a class="header-link-text" href="contact.php">Contact</a>
        </div>
        <div class="nav-left">
            <a class="login" href="login.php">Login</a>
        </div>
    </nav>
</header>
<body>
<div class = "center-box">
    <div class = "login-container">
        <h1> Instellingen </h1>
        <form method="post" class="form-login">
            <div class="section2">
            <div class="price-form">
                <label for="fName">Prijs per persoon</label>
                <input class="input-settings" id="price" type="number" min='0' max='9999' name='price'
                       value='<?php if (isset($_POST['price']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['price'];
                       } else {
                           echo $settings[0]['price'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['price'])) {
                    echo $errors['price'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="timeslot-begin-1-form">
                <label for="fName">Begin tijd tijdslot 1</label>
                <input class="input-settings" id="timeslotB1" type="time" name='timeslotB1'
                       value='<?php if (isset($_POST['timeslotB1']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['timeslotB1'];
                       } else {
                           echo $settings[0]['timeSlotBegin1'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['timeslotB1'])) {
                    echo $errors['timeslotB1'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="timeslot-end-1-form">
                <label for="fName">Eind tijd tijdslot 1</label>
                <input class="input-settings" id="timeslotE1" type="time" name='timeslotE1'
                       value='<?php if (isset($_POST['timeslotE1']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['timeslotE1'];
                       } else {
                           echo $settings[0]['timeSlotEnd1'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['timeslotE1'])) {
                    echo $errors['timeslotE1'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="timeslot-begin-2-form">
                <label for="fName">Begin tijd tijdslot 2</label>
                <input class="input-settings" id="timeslotB2" type="time" name='timeslotB2'
                       value='<?php if (isset($_POST['timeslotB2']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['timeslotB2'];
                       } else {
                           echo $settings[0]['timeSlotBegin2'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['timeslotB2'])) {
                    echo $errors['timeslotB2'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="timeslot-end-2-form">
                <label for="fName">Eind tijd tijdslot 2</label>
                <input class="input-settings" id="timeslotE2" type="time" name='timeslotE2'
                       value='<?php if (isset($_POST['timeslotE2']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['timeslotE2'];
                       } else {
                           echo $settings[0]['timeSlotEnd2'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['timeSlotEnd2'])) {
                    echo $errors['timeSlotEnd2'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="min-guest-form">
                <label for="minGuest">Minimaal aantal gasten</label>
                <input class="input-settings" id="minGuest" type="number" min='0' max='99' name='minGuest'
                       value='<?php if (isset($_POST['minGuest']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['minGuest'];
                       } else {
                           echo $settings[0]['minGuest'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['minGuest'])) {
                    echo $errors['minGuest'];
                } else {
                    echo '';
                } ?>
            </p>
            <div class="max-guest-form">
                <label for="minGuest">Maximaal aantal gasten</label>
                <input class="input-settings" id="minGuest" type="number" min='2' max='99' name='maxGuest'
                       value='<?php if (isset($_POST['maxGuest']) and count($errors) > 0) {
                           //echo $settings[0]['price'];
                           echo $_POST['maxGuest'];
                       } else {
                           echo $settings[0]['maxGuest'];
                       }
                       ?>'>
            </div>
            <p class="error">
                <?php if (isset($errors['maxGuest'])) {
                    echo $errors['maxGuest'];
                } else {
                    echo '';
                } ?>
            </p>
            <button type="submit">Opslaan</button>
            <a href="admin.php">Terug</a>
            </div>
        </form>
    </div>
<p class="succes"><?php if (isset($succes)) {
        echo $succes;
    } ?></p>
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
