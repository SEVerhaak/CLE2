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
print_r($settings);


// INSERT INTO `settings` (`id`, `price`, `timeSlotBegin1`, `timeSlotEnd1`, `timeSlotBegin2`, `timeSlotEnd2`, `minGuest`, `maxGuest`)
// VALUES ('1', '100', '12:00:00', '16:00:00', '18:00:00', '22:00:00', '2', '16');
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
                <a class="header-link-text" href="#">Over ons</a>
                <a class="header-link-text" href="#">Nieuws</a>
                <a class="header-link-text" href="#">Contact</a>
            </div>
            <div class="nav-left">
                <a class="login" href="login.php">Login</a>
            </div>
        </nav>
    </header>
    <body>
    <form>
        <div class="price-form">
            <label for="fName">Prijs per persoon</label>
            <input class="input-settings" id="price" type="number" min='0' max='9999' name='price' value='<?php if (isset($_POST[''])) {
                echo $settings[0]['price'];
            }
            ?>'>
        </div>
        <p class="error">
        </p>
        <div class="timeslot-begin-1-form">
            <label for="fName">Begin tijd tijdslot 1</label>
            <input class="input-settings" id="timeslotB1" type="time" name='timeslotB1' value=''>
        </div>
        <p class="error">
        </p>
        <div class="timeslot-end-1-form">
            <label for="fName">Eind tijd tijdslot 1</label>
            <input class="input-settings" id="timeslotE1" type="time" name='timeslotE1' value=''>
        </div>
        <p class="error">
        </p>
        <div class="timeslot-begin-2-form">
            <label for="fName">Begin tijd tijdslot 2</label>
            <input class="input-settings" id="timeslotB2" type="time" name='timeslotB2' value=''>
        </div>
        <p class="error">
        </p>
        <div class="timeslot-end-2-form">
            <label for="fName">Eind tijd tijdslot 2</label>
            <input class="input-settings" id="timeslotE2" type="time" name='timeslotE2' value=''>
        </div>
        <p class="error">
        <div class="min-guest-form">
            <label for="minGuest">Prijs per persoon</label>
            <input class="input-settings" id="minGuest" type="number" min='0' max='99' name='minGuest' value=''>
        </div>
        <p class="error">
        </p>
        <div class="max-guest-form">
            <label for="minGuest">Prijs per persoon</label>
            <input class="input-settings" id="minGuest" type="number" min='2' max='99' name='maxGuest' value=''>
        </div>
        <p class="error">
        </p>
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
    </html>
<?php
