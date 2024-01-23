<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Include library files
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function sendEmail($adres, $amountPeople, $service, $reservationBeginTime, $reservationEndTime, $fName, $lName, $extraInfo, $reservationDate){

// Create an instance; Pass `true` to enable exceptions
    $mail = new PHPMailer;

// Server settings
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output
    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->Host = 'smtp.strato.de';           // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = 'info@greenberrystudio.com';       // SMTP username
    $mail->Password = 'Kaasisbaas48';         // SMTP password
    $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                          // TCP port to connect to

// Sender info
    $mail->setFrom('info@greenberrystudio.com', 'DeniseKookt! Reserveringen');

    $emailUser = '';
// Add a recipient
    $mail->addAddress($adres);

//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

// Set email format to HTML
    $mail->isHTML(true);

// Mail subject
    $mail->Subject = 'Reservering Denise Kookt! voor '.$reservationDate;

// Mail body content
    $bodyContent = '<h2>Bedankt voor je reservering bij Denise Kookt!</h2>';
    $bodyContent .= '<p>Datum reservering: '.$reservationDate. ' om: '.$reservationBeginTime.'-'.$reservationEndTime.'</p>'.
        '<p>Hoeveelheid mensen: '.$amountPeople.'</p>'.
        '<p>Type reservering: '.$service.'</p>'.
        '<p>Bijzonderheden: '.$extraInfo.'</p>'.
        '<p>Binnekort krijgt u nog een mail met meer informatie </p>'.
        '<p>Voor meer vragen mail: info@denisekookt.nl</p>'.
        '<p>Met vriendelijke groet, Denise Kookt!</p>';
    $mail->Body    = $bodyContent;

// Send email
    if(!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
    } else {
        echo 'Message has been sent.';
    }
}

?>
