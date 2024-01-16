<?php
// required when working with sessions
session_start();

/** @var mysqli $db */
require_once 'includes/database.php';

$login = false;

$errorEmail = '';
$errorPassword = '';
// Is user logged in?

if (isset($_POST['submit'])) {


    // Get form data (Info uit database halen)
    $email = $_POST['email']; //Email uit database halen
    $password = $_POST['password']; //Password uit database halen

    // Server-side validation
    if (isset($_POST['email'], $_POST['password'])) { //Controleren of email en password is ingevuld
        if (empty($_POST['email'])) {
            $errorEmail = 'Email cannot be empty'; // Als email leeg is toon dit
        }

        if (empty($_POST['password'])) {
            $errorPassword = 'Password cannot be empty'; // als password leeg is toon dit
        }
    }

    // If data valid
    if (empty($errorEmail) && empty($errorPassword)) { // Als email en password niet leeg is

        // SELECT the user from the database, based on the email address.
        $query = "SELECT * FROM users WHERE email = '$email'"; // selecteer de email uit de db
        $result = mysqli_query($db, $query); // Er wordt een sql query uitgevoerd

        // check if the user exists
        if ($result) {

            // Get user data from result
            $user = mysqli_fetch_assoc($result);

            // Check if the provided password matches the stored password in the database
            if (password_verify($password, $user['password'])) {

                // Store the user in the session
                $_SESSION['user'] = [
                    'id' => $user['user_id'],
                    'admin' => $user['isAdmin'],
                ];

                // Redirect to secure page
                header('Location: index.php');
                exit;
            } else {
                // Credentials not valid
                require_once 'includes/validation.php';
            }
        } else {
            // User doesn't exist
            require_once 'includes/validation.php';
        }
    } else {
        // Handle database error
        require_once 'includes/validation.php';
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

    <title>Log in</title>
</head>
<!-- Header -->
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
            <a class="login" href="#">Login</a>
        </div>
    </nav>
</header>
<body>

<!-- form -->
<div class="center-box">
    <div class="login-container">
        <p>Terug naar de <a href="index.php">Homepagina</a></p>
        <h1 class="title">Log in</h1>
        <form class="form-login" action="" method="post">

            <label class="label" for="email">Email</label>
            <input class="input" id="email" type="text" name="email" value="<?= isset($email) ? $email : '' ?>"/>
            <p>
                <?php echo $errorEmail ?>
            </p>

            <label class="label" for="password">Password</label>
            <input class="input" id="password" type="password" name="password"/>
            <p class="help is-danger">
                <?php echo $errorPassword ?>
            </p>

            <button type="submit" name="submit">Log in With Email</button>

        </form>
        <section>
            <p><a href="registeren.php">Registreren</a></p>
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



