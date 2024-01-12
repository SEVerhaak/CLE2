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

//                // Store the user in the session
//                $_SESSION['user'] = [
//                    'name' => $user['first_name'],
//                    'email' => $user['email'],
//                    'id' => $user['user_id'],
//                ];

                // Redirect to secure page
                header('Location: admin.php');
                exit;
            } else {
                // Credentials not valid
                require_once 'includes/validation.php';
            }
        } else {
            // User doesn't exist
            require_once 'includes/validation.php';
        }
    }
    else {
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
    <link rel="stylesheet" href="">
    <title>Log in</title>
</head>
<body>
<section class="section">
    <div class="container content">
        <p>Terug naar de <a href="index.php">Homepagina</a></p>
        <h2 class="title">Log in</h2>

        <section class="columns">
            <form class="column is-6" action="" method="post">

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="email">Email</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="email" type="text" name="email" value="<?= isset($email) ? $email : '' ?>" />
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?= isset($errors['loginFailed']) ? $errors['loginFailed'] : '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="password">Password</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="password" type="password" name="password"/>
                                <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?= isset($errors['loginFailed']) ? $errors['loginFailed'] : '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal"></div>
                    <div class="field-body">
                        <button class="button is-link is-fullwidth" type="submit" name="submit">Log in With Email</button>
                    </div>
                </div>

            </form>
        </section>
        <section>
            <p><a href="register.php">Registreren</a></p>
        </section>


    </div>
</section>
</body>
</html>



