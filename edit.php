<?php
/** @var mysqli $db */
// Setup connection with database
require_once 'includes/database.php';

if(!isset($_GET['id'])){
    header('Location: index.php');
    exit;
}
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
// check of alles is ingevuld
$errorFirstname = '';
$errorLastname = '';
$errorEmail = '';
$errorPhonenumber = '';
$errorIsAdmin = '';

if (isset($_POST['submit'])) {

    // Get form data
    $firstName = mysqli_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_escape_string($db, $_POST['lastName']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $phoneNumber = mysqli_escape_string($db, $_POST['phoneNumber']);

    $isAdmin = mysqli_escape_string($db, $_POST['isAdmin']);
    $index = $_POST['id'];

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
        if (empty($_POST['phoneNumber'])) {
            $errorPhonenumber = 'phoneNumber cannot be empty';
        }

        if (empty($_POST['isAdmin'])) {
            $errorEmail = 'admin cannot be empty';
        }
    }

    // If data valid
    if (empty($errorFirstname) && empty($errorLastname) && empty($errorEmail) && empty($errorPassword) && empty($errorPhonenumber) && empty($errorIsAdmin)) {
        // create a secure password, with the PHP function password_hash()


        // store the new user in the database.
        $sql ="UPDATE `users` SET `firstName` = '$firstName', `lastName` = '$lastName', `email` = '$email', `phoneNumber` = '$phoneNumber', `isAdmin` = '$isAdmin' WHERE `id` = '$index'";


        $result = mysqli_query($db, $sql);
        // If query succeeded
        if ($result) {
            // Redirect to login page
            header("Location: users.php");
            // Exit the code
            $db->close();
        }


    }
}else if (isset($_GET['id']) && $_GET['id'] !== ''){
    $index = $_GET['id'];


// select de album emt de juiste id van de database
    $query = "SELECT * FROM `reservations` WHERE id = '$index'";
    $result = mysqli_query($db, $query)
    or die('Error '.mysqli_error($db).' with query '.$query);

    if(mysqli_num_rows($result)== 1){
        $user = mysqli_fetch_assoc($result);
    }else{
        header('Location: index.php');
        exit;
    }
    $firstName = $user['firstName'];
    $lastName = $user['lastName'];
    $email = $user['email'];
    $phoneNumber = $user['phoneNumber'];
    $isAdmin = $user['isAdmin'];

}

mysqli_close($db);
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
        <a href="#mail"><img src="img/mail.png"></a>
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
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
                <?php echo $errorPhonenumber ?>
            </p>
            <label class="label" for="phoneNumber">Telefoonnummer</label>
            <input class="input" id="phoneNumber" type="number" name="phoneNumber"
                   value="<?= isset($phoneNumber) ? $phoneNumber : '' ?>"/>

            <!-- isAdmin -->
            <p class="warning">
                <?php echo $errorIsAdmin ?>
            </p>
            <label class="label" for="isAdmin">isAdmin</label>
            <input class="input" id="isAdmin" type="number" name="isAdmin"
                   value="<?= isset($isAdmin) ? $isAdmin : '' ?>"/>

            <!-- Submit -->
            <input type = "hidden" name = "id" value = "<?= htmlentities($index) ?>" />
            <button class="button is-link is-fullwidth" type="submit" name="submit">Opslaan</button>
        </form>
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
