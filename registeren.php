<?php
session_start();
/** @var mysqli $db */
require_once "includes/database.php";

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
    if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['phoneNumber'], $_POST['isAdmin'])) {
        if (empty($_POST['firstName'])) {
            $errorFirstname = 'firstname cannot be empty';
        }
        if (empty($_POST['lastName'])) {
            $errorLastname = 'lastname cannot be empty';
        }
        if (empty($_POST['email'])) {
            $errorEmail = 'Email cannot be empty';
        }
        if (empty($_POST['password'])) {
            $errorPassword = 'password cannot be empty';
        }
        if (empty($_POST['phoneNumber'])) {
            $errorPhonenumber = 'phoneNumber cannot be empty';
        }
    }

    // If data valid
    if (empty($errorFirstname) && empty($errorLastname) && empty($errorEmail) && empty($errorPassword) && empty($errorPhonenumber) && empty($errorIsAdmin)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash("$password", PASSWORD_BCRYPT, ['cost' => 12]);

        // store the new user in the database.
        $sql = "INSERT INTO users (firstName, lastName, email, password, phoneNumber, isAdmin) VALUES ('$firstName', '$lastName', '$email', '$password', '$phoneNumber', '0')";


        $result = mysqli_query($db, $sql);
        // If query succeeded
        if ($result) {
            // Redirect to login page
            header("Location: login.php");
            // Exit the code
            $db->close();
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
<!-- form  -->
<div class="center-box">
    <div class="login-container">
        <h2 class="title">Register With Email</h2>
        <form class="form-login" action="" method="post">

            <!-- First name -->
            <p class="warning" >
                <?php echo $errorFirstname ?>
            </p>
            <label class="label" for="firstName">Voornaam</label>
            <input class="input" id="firstName" type="text" name="firstName"
                   value="<?= isset($firstName) ? $firstName : '' ?>"/>

            <!-- Last name -->
            <p class="warning">
                <?php echo $errorLastname ?>
            </p>
            <label class="label" for="lastName">Achternaam</label>
            <input class="input" id="lastName" type="text" name="lastName"
                   value="<?= isset($lastName) ? $lastName : '' ?>"/>

            <!-- Email -->
            <p class="warning">
                <?php echo $errorEmail ?>
            </p>
            <label class="label" for="email">Email</label>
            <input class="input" id="email" type="text" name="email"
                   value="<?= isset($email) ? $email : '' ?>"/>

            <!-- Password -->
            <p class="warning">
                <?php echo $errorPassword ?>
            </p>
            <label class="label" for="password">Wachtwoord</label>
            <input class="input" id="password" type="password" name="password"/>

            <!-- Phonenumber -->
            <p class="warning">
                <?php echo $errorPhonenumber ?>
            </p>
            <label class="label" for="phoneNumber">Telefoonnummer</label>
            <input class="input" id="phoneNumber" type="number" name="phoneNumber"
                   value="<?= isset($phonenumber) ? $phonenumber : '' ?>"/>

            <!-- Submit -->
            <button class="button is-link is-fullwidth" type="submit" name="submit">Register</button>
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
        <p class="footer-social-text">Socials</p>
    </div>
</footer>
</html>
