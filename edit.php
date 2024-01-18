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
$firstName = '';
$lastName = '';
$reservationType = '';
$reservationDate = '';
$reservationBeginTime = '';
$reservationEndTime = '';
$amountPeople = '';
$extraInfo = '';

if (isset($_POST['submit'])) {

    $reservationType = mysqli_escape_string($db, $_POST['reservationType']);
    $reservationDate = mysqli_escape_string($db, $_POST['reservationDate']);
    $reservationBeginTime = mysqli_escape_string($db, $_POST['reservationBeginTime']);
    $reservationEndTime = mysqli_escape_string($db, $_POST['reservationEndTime']);
    $amountPeople = mysqli_escape_string($db, $_POST['amountPeople']);
    $extraInfo = mysqli_escape_string($db, $_POST['extraInfo']);
    $index = $_POST['id'];

    $errors = [];
    // Server-side validation
        if (empty($_POST['reservationType'])) {
            $errors['reservationType'] = 'Reservation type cannot ben empty';
        }
        if (empty($_POST['reservationDate'])) {
            $errors['reservationDate'] = 'reservation date cannot be empty';
        }
        if (empty($_POST['reservationBeginTime'])) {
            $errors['reservationBeginTime'] = 'reservation begin time cannot be empty';
        }
        if (empty($_POST['reservationEndTime'])) {
        $errors['reservationEndTime'] = 'reservation end time cannot be empty';
        }
    if (empty($_POST['amountPeople'])) {
        $errors['amountPeople'] = 'amount people cannot be empty';
    }



    // If data valid
if(empty($errors))  {


        // store the new user in the database.
        $sql ="UPDATE `reservations` SET `reservationType` = '$reservationType', `reservationDate` = '$reservationDate', `reservationBeginTime` = '$reservationBeginTime', `reservationEndTime` = '$reservationEndTime', `amountPeople` = '$amountPeople', `extraInfo` = '$extraInfo' WHERE `id` = '$index'";


        $result = mysqli_query($db, $sql);
        // If query succeeded
        if ($result) {
            // Redirect to login page
            header("Location: admin_reservations.php");
            // Exit the code
            $db->close();
        }


    }
}else if (isset($_GET['id']) && $_GET['id'] !== ''){
    $index = $_GET['id'];
// select de album emt de juiste id van de database
    $query = "SELECT reservations.id, amountPeople, reservationDate, reservationBeginTime, reservationEndTime, reservationType, extraInfo, users.firstName, users.lastName FROM `reservations` JOIN users on userId = users.id WHERE reservations.id = '$index'";
    $result = mysqli_query($db, $query)
    or die('Error '.mysqli_error($db).' with query '.$query);

    if(mysqli_num_rows($result)== 1){
        $reservation = mysqli_fetch_assoc($result);
    }else{
        header('Location: index.php');
        exit;
    }
    $firstName = $reservation['firstName'];
    $lastName = $reservation['lastName'];
    $reservationType = $reservation['reservationType'];
    $reservationDate = $reservation['reservationDate'];
    $reservationBeginTime = $reservation['reservationBeginTime'];
    $reservationEndTime = $reservation['reservationEndTime'];
    $amountPeople = $reservation['amountPeople'];
    $extraInfo = $reservation['extraInfo'];

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
        <a href="settings.php"><img src="img/settings.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
    </div>
<?php } ?>
<!-- form  -->
<div class="center-box">
    <div class="login-container">
        <h2 class="title">Verander Reservering van: <?= $firstName.' '.$lastName ?></h2>
        <form class="form-login" action="" method="post">

            <!-- Reservations Type -->
            <p class="warning" >
                <?php if(isset($errors['reservationType'])){
                    echo $errors['reservationType'];
                } ?>
            </p>
            <label class="label" for="reservationType">Type reservering</label>
            <input class="input" id="reservationType" type="text" name="reservationType"
                   value="<?= isset($reservationType) ? $reservationType : '' ?>"/>

            <!-- Last name -->
            <p class="warning">
                <?php if(isset($errors['reservationDate'])){
                    echo$errors['reservationDate'];
                } ?>
            </p>
            <label class="label" for="reservationDate">Datum reservering</label>
            <input class="input" id="reservationDate" type="date" name="reservationDate"
                   value="<?= isset($reservationDate) ? $reservationDate : '' ?>"/>

            <!-- Email -->
            <p class="warning">
                <?php if(isset($errors['reservationBeginTime'])){
                    echo $errors['reservationBeginTime'];
                } ?>
            </p>
            <label class="label" for="reservationBeginTime">Begin tijd reservering</label>
            <input class="input" id="reservationBeginTime" type="time" name="reservationBeginTime"
                   value="<?= isset($reservationBeginTime) ? $reservationBeginTime : '' ?>"/>

            <!-- Password -->
            <p class="warning">
                <?php if(isset($errors['reservationEndTime'])){
                    echo $errors['reservationEndTime'];
                } ?>
            </p>
            <label class="label" for="reservationEndTime">Eind tijd reservering</label>
            <input class="input" id="reservationEndTime" type="time" name="reservationEndTime"
                   value="<?= isset($reservationEndTime) ? $reservationEndTime : '' ?>"/>

            <!-- isAdmin -->
            <p class="warning">
                <?php if(isset($errors['amountPeople'])){
                    echo $errors['amountPeople'];
                } ?>

            </p>
            <label class="label" for="amountPeople">Hoeveelheid mensen</label>
            <input class="input" id="amountPeople" type="number" name="amountPeople"
                   value="<?= isset($amountPeople) ? $amountPeople : '' ?>"/>

            <label for="extraInfo">Bijzonderheden?</label>
            <textarea class="extraInfo" id="extraInfo" type="text" name="extraInfo" rows="10"><?= isset($extraInfo) ? $extraInfo : '' ?></textarea>


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
        <a href = "https://www.instagram.com/denisekookt/?hl=nl"><img class="insta" src="img/insta.png"></a>
    </div>
</footer>
</html>
