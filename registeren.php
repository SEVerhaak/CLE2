<?php
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

    $isAdmin = mysqli_escape_string($db, $_POST['isAdmin']);

    // Server-side validation
    if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['phoneNumber'], $_POST['isAdmin'])) {
        if (empty($_POST['firstName'])) {
            $errorEmail = 'firstname cannot be empty';
        }
        if (empty($_POST['lastName'])) {
            $errorEmail = 'lastname cannot be empty';
        }
        if (empty($_POST['email'])) {
            $errorEmail = 'Email cannot be empty';
        }
        if (empty($_POST['password'])) {
            $errorEmail = 'password cannot be empty';
        }
        if (empty($_POST['phoneNumber'])) {
            $errorEmail = 'phoneNumber cannot be empty';
        }

        if (empty($_POST['isAdmin'])) {
            $errorEmail = 'admin cannot be empty';
        }
    }

    // If data valid
    if (empty($errorFirstname) && empty($errorLastname) && empty($errorEmail) && empty($errorPassword) && empty($errorPhonenumber) && empty($errorIsAdmin)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash("$password", PASSWORD_BCRYPT, ['cost' => 10]);

        // store the new user in the database.
        $sql = "INSERT INTO users (firstName, lastName, email, password, phoneNumber, isAdmin) VALUES ('$firstName', '$lastName', '$email', '$password', '$phoneNumber', '$isAdmin')";


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
<body>
<!-- form  -->
<div class="center-box">
    <div class="login-container">
        <h2 class="title">Register With Email</h2>
        <form class="form-login" action="" method="post">
            <!-- First name -->
            <label class="label" for="firstName">First name</label>
            <input class="input" id="firstName" type="text" name="firstName"
                   value="<?= isset($firstName) ? $firstName : '' ?>"/>
            <p class="help is-danger">
                <?php echo $errorFirstname ?>
            </p>
            <!-- Last name -->

            <label class="label" for="lastName">Last name</label>
            <input class="input" id="lastName" type="text" name="lastName"
                   value="<?= isset($lastName) ? $lastName : '' ?>"/>


            <p class="help is-danger">
                <?php echo $errorLastname ?>
            </p>

            <!-- Email -->
            <label class="label" for="email">Email</label>
            <input class="input" id="email" type="text" name="email"
                   value="<?= isset($email) ? $email : '' ?>"/>

            <p class="help is-danger">
                <?php echo $errorEmail ?>
            </p>
            <!-- Password -->

            <label class="label" for="password">Password</label>

            <input class="input" id="password" type="password" name="password"/>

            <p class="help is-danger">
                <?php echo $errorPassword ?>
            </p>

            <!-- Phonenumber -->

            <label class="label" for="phoneNumber">Phonenumber</label>

            <input class="input" id="phoneNumber" type="number" name="phoneNumber"
                   value="<?= isset($phonenumber) ? $phonenumber : '' ?>"/>

            <p class="help is-danger">
                <?php echo $errorPhonenumber ?>
            </p>
            <!-- isAdmin -->

            <label class="label" for="isAdmin">isAdmin</label>

            <input class="input" id="isAdmin" type="number" name="isAdmin"
                   value="<?= isset($isAdmin) ? $isAdmin : '' ?>"/>

            <p class="help is-danger">
                <?php echo $errorIsAdmin ?>
            </p>


            <!-- Submit -->

            <button class="button is-link is-fullwidth" type="submit" name="submit">Register</button>
        </form>
        <section>
            <p>Heb je al een account?<a href="login.php"> Inloggen</a></p>
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
