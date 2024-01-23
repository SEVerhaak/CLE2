<?php
// required when working with sessions
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/** @var mysqli $db */
require_once 'includes/database.php';
require_once 'adminmail.php';

$login = false;
if (isset($_GET['id'])) {
    $userID = $_GET['id'];
    $errors = [];
// Is user logged in?
    $sqlUserQuery = "SELECT `id`, `email`, `firstName`, `lastName` FROM `users` WHERE `id` = '$userID'";
    $result = mysqli_query($db, $sqlUserQuery)
    or die('Error ' . mysqli_error($db) . ' with query ' . $sqlUserQuery);
    $userData = [];
    while ($row = mysqli_fetch_assoc($result))
        $userData[] = $row;
    if (count($userData) === 0) {
        //header("Location: index.php");
    }
    if (isset($_POST['submit'])) {
        // Server-side validation
        $email = $userData[0]['email'];
        if (empty($_POST['subject'])) {
            $errors['subject'] = 'Het bericht heeft geen onderwerp!'; // Als email leeg is toon dit
        } else {
            $subject = $_POST['subject'];
        }
        if (empty($_POST['content'])) {
            $errors['content'] = 'het bericht heeft geen content!'; // Als email leeg is toon dit
        } else {
            $content = $_POST['content'];
        }
        if (empty($errors)) { // Als email en password niet leeg is
            sendAdminEmail($email, $subject, $content);
        }
    }

} else {
    header("location:admin.php");
}

?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
            <a href="index.php"><img class="logo" src="img/logo_dk.png"></a>
            <div class="header-links">
                <a class="header-link-text" href="reservation.php">Reserveren</a>
                <a class="header-link-text" href="about.php">Over ons</a>
                <a class="header-link-text" href="news.php">Nieuws</a>
                <a class="header-link-text" href="contact.php">Contact</a>
            </div>
        </div>
        <div class="nav-left">
            <?php if (!isset($_SESSION['user'])) { ?>
                <a class="login" href="login.php">Login</a>
            <?php } else { ?>
                <a class="login" href="logout.php">Log uit</a>
                <a class="login" href="user-reservations.php">Mijn reserveringen</a>
            <?php } ?>

        </div>
    </nav>
</header>
<body>
<?php if (isset($_SESSION['user']['admin'])) { ?>
    <div class="sidebar">
        <a href="admin.php"><img src="img/home.png"></a>
        <a href="users.php"><img src="img/users.png"></a>
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
    </div>
<?php } ?>


<!-- form -->
<div class="center-box">


    <div class="login-container">
        <h1 class="title">Email versturen</h1>
        <form class="form-login" action="" method="post">

            <label class="label" for="email">Email</label>
            <input class="input" id="email" type="text" name="email" value="<?= $userData[0]['email'] ?>" disabled>
            <p class="error">
                <?php echo '' ?>
            </p>

            <label class="label" for="subject">Onderwerp</label>
            <input class="input" id="subject" type="text" name="subject">
            <p class="error">
                <?php echo '' ?>
            </p>
            <label class="label" for="content">Tekst</label>
            <textarea class="input" id="content" name="content"></textarea>
            <p class="error">
                <?php echo '' ?>
            </p>
            <div class="flex-side">
                <a href="admin.php">Terug</a>
                <button type="submit" name="submit">Verstuur</button>
            </div>
        </form>
    </div>
</div>

</body>
<footer>
    <div class="footer-style">
        <img class="logo" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <a href="https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>




