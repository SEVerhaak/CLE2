<?php
// sessie starten
session_start();
// import variables
/** @var array $db */
/** @var array $takendates */
// vereiste bestanden
require_once 'includes/database.php';
require_once 'includes/functions.php';


// tijdelijke debug opties
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// controleer of de gebruiker ingelogd is en admin is
if (!isset($_SESSION['user'])) {
    header('location: index.php');
} else if (!isset($_SESSION['user']['admin'])) {
    header('location: index.php');
} else if ($_SESSION['user']['admin'] !== '1') {
    header('location: index.php');
} else {
    // query om alle reserveringen die gemaakt zijn om datums te blokeren eruit te halen
    $query = "SELECT reservations.id, amountPeople, reservationDate, reservationBeginTime, reservationEndTime, reservationType, extraInfo, users.firstName, users.lastName, users.phoneNumber, users.email FROM `reservations` JOIN users on userId = users.id WHERE amountPeople = '0' ORDER by reservationDate ";
    $result = mysqli_query($db, $query) or die('Error ' . htmlentities(mysqli_error($db)) . ' with query ' . htmlentities($query));

    $reservations = [];

    while ($reservation = mysqli_fetch_assoc($result)) {
        $reservations[] = $reservation;
    }

// Sluit de resultaatset, maar laat de verbinding open
    mysqli_free_result($result);

    // default waardes voor datum blokkerende reserveringen
    $userID = 1;
    $amount_people = 0;
    $timeBegin = strtotime('00:00:00');
    $timeEnd = strtotime('00:00:00');
    $service = 'n.a';
    $extraInfo = 'Datum geblokkeerd';
    $timeBegin = date("h:i:s", $timeBegin);
    $timeEnd = date("h:i:s", $timeEnd);
    $checkMultiple = false;

    $currentTime = time();
    $currentTimeSQL = date("Y-m-d h:i:s", $currentTime);
    $currentTimeHTML = date("Y-m-d", $currentTime);

    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['beginDate'])) {
            $errors['startDate'] = 'Begindatum moet ingevuld zijn';
        } else {
            $beginDate = $_POST['beginDate'];
        }
        if (isset($_POST['checkMultiple'])) {
            $checkMultiple = true;
            if (empty($_POST['endDate'])) {
                $errors['endDate'] = 'Einddatum moet ingevuld zijn';
            } else {
                $endDate = $_POST['endDate'];
            }
        }
        //print_r($beginDate);
        //print_r($endDate);
        if ($checkMultiple) {
            $begin = new DateTime($beginDate);
            $end = new DateTime($endDate);
            $end = $end->modify('+1 day');

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);



            foreach ($period as $dt) {
                $date = $dt->format("Y-m-d");
                $sqlDate = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType, extraInfo) 
                VALUES ('$userID','$amount_people','$date','$timeBegin','$timeEnd','$currentTimeSQL','$service', '$extraInfo')";

                if (mysqli_query($db, $sqlDate)) {
                    //echo "New record created successfully";
                    //header('Location: admin.php');
                    $succes = true;
                } else {
                    echo "Error: " . $sqlDate . "<br>" . mysqli_error($db);
                    $errorMessage = "An error has occurred";
                    $succes = false;
                }

            }

            if ($succes) {
                $succesMessage = 'Datums zijn gewijzigd naar niet beschikbaar';
            }
            mysqli_close($db);

        } else {

            $sqlDateSingle = "INSERT INTO `reservations`(userId, amountPeople, reservationDate, reservationBeginTime, reservationEndTime ,reservationCreationDate, reservationType, extraInfo)
        VALUES ('$userID','$amount_people','$beginDate','$timeBegin','$timeEnd','$currentTimeSQL','$service', '$extraInfo')";
            if (mysqli_query($db, $sqlDateSingle)) {
                // echo "New record created successfully";
                //header('Location: admin.php');
                $succes = true;
            } else {
                echo "Error: " . $sqlDateSingle . "<br>" . mysqli_error($db);
                $errorMessage = "An error has occurred";
                $succes = false;
            }
            if ($succes) {
                $succesMessage = 'Datums zijn gewijzigd naar niet beschikbaar';
                header('Location: adminSelectDates.php');
            }
            mysqli_close($db);


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
    <link href="css/style.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <title>Denise Kookt!</title>
</head>
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
<?php if (isset($_SESSION['user']['admin'])) { ?>
    <div class="sidebar">
        <a href="admin.php"><img src="img/home.png"></a>
        <a href="users.php"><img src="img/users.png"></a>
        <a href="testCalender.php"><img src="img/agenda.png"></a>
        <a href="admin_reservations.php"><img src="img/dollar.png"></a>
        <a href="settings.php"><img src="img/settings.png"></a>
        <a href="adminSelectDates.php"><img src="img/trash.png"></a>
    </div>
<?php } ?>
<div class="date-reservation-box">
    <div class = "info-reservation1">
    <h2>Selecteer een datum om de beschikbaarheid ervan aan te passen</h2>
    <form action="#" method="post">
        <label for="beginDate">Begin datum:</label>
        <input type="date" id="beginDate" name="beginDate" min="<?= $currentTimeHTML ?>">
        <p class="error"><?php if(isset($errors['startDate'])){
                echo $errors['startDate'];
            } else{
                echo '';
            } ?></p>
        <label for="checkMultiple">Reeks datums niet beschikbaar opgeven</label>
        <input type="checkbox" id="checkMultiple" name="checkMultiple">
        <br>
        <label for="endDate">Eind datum:</label>
        <input type="date" id="endDate" name="endDate" min="<?= $currentTimeHTML ?>" >
        <p class="error"><?php if(isset($errors['endDate'])){
                echo $errors['endDate'];
            } else{
                echo '';
            } ?></p>
        <p class="succes"><?php if(isset($succesMessage)){
                echo $succesMessage;
            } else{
                echo '';
            } ?></p>
        <button type="submit">Opslaan</button>
    </form>
    </div>
    <div class = "info-reservation-box1">
    <?php foreach ($reservations as $index => $reservation) { ?>
        <div class="info-reservation2">
            <h2>Datum: <?= date("D F j, Y", strtotime($reservations[$index]['reservationDate'])) ?></h2>
            <a href="delete.php?id=<?= $reservations[$index]['id'] ?>&page=adminSelectDates">Verwijder datum</a>
        </div>
    <?php } ?>
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
<script>
    // checkbox element
    let checkbox = document.getElementById('checkMultiple');
    // 2e datum invul optie
    let secondDateBox = document.getElementById('endDate');
    // 2e datum disabled bool
    secondDateBox.disabled = true;

    // op verandering van de waarde van de checkbox; zet de 2e datum invul optie op enabled/disabled (flip-flop)
    checkbox.addEventListener('change', (event) => {
        secondDateBox.disabled = !event.currentTarget.checked;
    })
</script>
</html>

