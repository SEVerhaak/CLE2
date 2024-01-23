<?php
session_start();
/** @var mysqli $db */
require_once "includes/database.php";

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$currentTime = time();
//huidige tijd met SQL&HTML formatting
$currentTimeSQL = date("Y-m-d h:i:s", $currentTime);

$errorFirstname = '';
$errorLastname = '';
$errorEmail = '';
$errorPassword = '';
$errorPhonenumber = '';
$errorIsAdmin = '';

if (isset($_POST['submit'])) {

    // Get form data
    $firstName = mysqli_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_escape_string($db, $_POST['lastName']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = mysqli_escape_string($db, $_POST['password']);
    $phoneNumber = mysqli_escape_string($db, $_POST['phoneNumber']);

    // Server-side validation
        if (empty($_POST['firstName'])) {
            $errorFirstname = 'Vul een naam in';
        }
        if (empty($_POST['lastName'])) {
            $errorLastname = 'Vul een naam in';
        }
        if (empty($_POST['email'])) {
            $errorEmail = 'Vul een e-mail in';
        }
        if (empty($_POST['password'])) {
            $errorPassword = 'Vul een wachtwoord in';
        }
        if (empty($_POST['phoneNumber'])) {
            $errorPhonenumber = 'Vul een geldig telefoonnummer in';
        }
        elseif (!isValidPhoneNumber($phoneNumber)) {
            $errorPhonenumber = 'Vul een geldig telefoonnummer in';
        }


    // If data valid
    if (empty($errorFirstname) && empty($errorLastname) && empty($errorEmail) && empty($errorPassword) && empty($errorPhonenumber) && empty($errorIsAdmin)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash("$password", PASSWORD_BCRYPT, ['cost' => 12]);

        // store the new user in the database.
        $sql = "INSERT INTO users (firstName, lastName, email, password, phoneNumber, creationDate ,isAdmin) VALUES ('$firstName', '$lastName', '$email', '$password', '$phoneNumber', '$currentTimeSQL', '0')";

        $result = mysqli_query($db, $sql);

        // If query succeeded
        if ($result) {
            // Als de registratie succesvol is, voer dan het volgende uit:
            $success_message = "Registratie succesvol! U kunt nu inloggen.";

            // Doorverwijzen naar login.php met het succesbericht
            header("Location: login.php?success=" . urlencode($success_message));
            exit(); // Zorg ervoor dat de code na de header() niet wordt uitgevoerd.
        }
    }
}

// Functie voor het controleren van een geldig telefoonnummer
function isValidPhoneNumber($phoneNumber) {
    // Verwijder eventuele spaties, streepjes, haakjes, en andere tekens
    $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Controleer of het nummer begint met "06" en gevolgd wordt door 8 cijfers
    $pattern = '/^06\d{8}$/';

    return (bool)preg_match($pattern, $cleanedPhoneNumber);
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

    <title>Registreren</title>
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
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>
<!-- form  -->
<div class="center-box">
    <div class="login-container">
        <h2 class="title">Registreren</h2>
        <form class="form-login" action="" method="post">

            <!-- First name -->

            <label class="label" for="firstName">Voornaam</label>
            <input class="input" id="firstName" type="text" name="firstName"
                   value="<?= isset($firstName) ? $firstName : '' ?>"/>
            <p class="error" >
                <?php echo $errorFirstname ?>
            </p>
            <!-- Last name -->

            <label class="label" for="lastName">Achternaam</label>
            <input class="input" id="lastName" type="text" name="lastName"
                   value="<?= isset($lastName) ? $lastName : '' ?>"/>
            <p class="error">
                <?php echo $errorLastname ?>
            </p>
            <!-- Email -->

            <label class="label" for="email">Email</label>
            <input class="input" id="email" type="text" name="email"
                   value="<?= isset($email) ? $email : '' ?>"/>
            <p class="error">
                <?php echo $errorEmail ?>
            </p>
            <!-- Password -->

            <label class="label" for="password">Wachtwoord</label>
            <input class="input" id="password" type="password" name="password"/>
            <p class="error">
                <?php echo $errorPassword ?>
            </p>
            <!-- Phonenumber -->

            <label class="label" for="phoneNumber">Telefoonnummer</label>
            <input class="input" id="phoneNumber" type="number" name="phoneNumber"
                   value="<?= isset($phonenumber) ? $phonenumber : '' ?>"/>
            <p class="error">
                <?php echo $errorPhonenumber ?>
            </p>
            <!-- Submit -->
            <button class="button is-link is-fullwidth" type="submit" name="submit">Registreer</button>
        </form>
        <section>
            <p>Heb je al een account?<a href="login.php">Inloggen</a></p>
        </section>
    </div>
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
