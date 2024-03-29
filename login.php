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
            $errorEmail = 'Vul een geldig e-mailadres in'; // Als email leeg is toon dit
        }

        if (empty($_POST['password'])) {
            $errorPassword = 'Vul een geldig wachtwoord in'; // als password leeg is toon dit
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
            if (!empty($user)) {
                if (password_verify($password, $user['password'])) {

                    // Store the user in the session
                    if ($user['isAdmin']) {
                        $_SESSION['user'] = [
                            'id' => $user['id'],
                            'admin' => $user['isAdmin'],
                        ];
                    } else {
                        $_SESSION['user'] = [
                            'id' => $user['id'],
                        ];

                    }
                    header('Location: admin.php');
                    exit;
                } else {
                    // Credentials not valid
                    $errorPassword = 'e-mail-adres of wachtwoord is onjuist';
                }
            } else {
                $errorPassword = 'e-mail-adres of wachtwoord is onjuist';
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

    <title>Log in</title>
</head>
<!-- Header -->
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
        <div class="navbar">
            <div class="containers nav-container">
                <input class="checkbox" type="checkbox" name="" id="" />
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <div class="logo">
                    <a href = "index.php"><img class="logo" src="img/logo_dk.png"></a>
                </div>
                <div class="menu-items">
                    <li><a class="header-link-text" href="reservation.php">Reserveren</a></li>
                    <li><a class="header-link-text" href="about.php">Over ons</a></li>
                    <li><a class="header-link-text" href="news.php">Nieuws</a></li>
                    <li><a class="header-link-text" href="contact.php">Contact</a></li>
                    <br>
                    <br>
                    <div class="nav-left-mobile">
                        <?php if(!isset($_SESSION['user'])){?>
                            <a class="login-mobile" href="login.php">Login</a>
                        <?php }else{ ?>
                            <a class="login-mobile" href = "logout.php">Log uit</a>
                            <a class="login-mobile" href = "user-reservations.php">Mijn reserveringen</a>
                        <?php } ?>
                    </div>
                </div>
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



<!-- form -->
<div class="center-box">


    <div class="login-container">
        <p>Terug naar de <a href="index.php">Homepagina</a></p>
        <?php
        // Controleer of er een succesbericht is ingesteld in de URL
        if (isset($_GET['success'])) {
            $success_message = $_GET['success'];
            echo '<p class="success-message">' . htmlspecialchars($success_message) . '</p>';
        }
        ?>
        <h1 class="title">Log in</h1>
        <form class="form-login" action="" method="post">

            <label class="label" for="email">Email</label>
            <input class="input" id="email" type="text" name="email" value="<?= isset($email) ? $email : '' ?>"/>
            <p class = "error">
                <?php echo $errorEmail ?>
            </p>

            <label class="label" for="password">Wachtwoord</label>
            <input class="input" id="password" type="password" name="password"/>
            <p class="error">
                <?php echo $errorPassword ?>
            </p>

            <button type="submit" name="submit">Log in</button>

        </form>
        <section>
            <p><a href="registeren.php">Registreren</a></p>
        </section>
    </div>
</div>

</body>
<footer>
    <div class="footer-style">
        <img class="logo-mobile" src="img/logo_dk.png">
        <p class="footer-main-text">Denise Kookt!</p>
        <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>



