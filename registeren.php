<?php
/** @var mysqli $db */
require_once "includes/database.php";

$errorFirstname = '';
$errorLastname = '';
$errorEmail = '';
$errorPassword = '';
$errorPhonenumber = '';
$errorIsAdmin = '';

if(isset($_POST['submit'])) {

    // Get form data
    $firstName = mysqli_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_escape_string($db, $_POST['lastName']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = mysqli_escape_string($db, $_POST['password']);
    $phoneNumber = mysqli_escape_string($db, $_POST['phoneNumber']);

    $isAdmin = mysqli_escape_string($db, $_POST['isAdmin']);

    // Server-side validation
    if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'],  $_POST['phoneNumber'], $_POST['isAdmin'])) {
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
    if (empty($errorFirstname)&&empty($errorLastname)&&empty($errorEmail)&&empty($errorPassword)&&empty($errorPhonenumber)&&empty($errorIsAdmin)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash("$password", PASSWORD_BCRYPT, ['cost' => 12]);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Registreren</title>
</head>
<body>

<section class="section">
    <div class="container content">
        <h2 class="title">Register With Email</h2>

        <section class="columns">
            <form class="column is-6" action="" method="post">

                <!-- First name -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="firstName">First name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="firstName" type="text" name="firstName" value="<?= isset($firstName) ? $firstName : '' ?>" />
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?php echo $errorFirstname ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Last name -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="lastName">Last name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="lastName" type="text" name="lastName" value="<?= isset($lastName) ? $lastName : '' ?>" />
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?php echo $errorLastname ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email -->
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
                                <?php echo $errorEmail ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Password -->
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
                                <?php echo $errorPassword ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Phonenumber -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="phoneNumber">Phonenumber</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="phoneNumber" type="number" name="phoneNumber" value="<?= isset($phonenumber) ? $phonenumber : '' ?>" />
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?php echo $errorPhonenumber ?>
                            </p>
                        </div>
                    </div>
                </div>


                <!-- isAdmin -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="isAdmin">isAdmin</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="isAdmin" type="number" name="isAdmin" value="<?= isset($isAdmin) ? $isAdmin : '' ?>" />
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?php echo $errorIsAdmin ?>
                            </p>
                        </div>
                    </div>
                </div>


                <!-- Submit -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal"></div>
                    <div class="field-body">
                        <button class="button is-link is-fullwidth" type="submit" name="submit">Register</button>
                    </div>
                </div>

            </form>

        </section>
        <section>
            <p>Heb je al een account?<a href="login.php"> Inloggen</a></p>
        </section>

    </div>
</section>
</body>
</html>
