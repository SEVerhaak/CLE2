<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include library files
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'includes/functions.php';

adminCheck();

function sendAdminEmail($adres, $subject, $content)
{

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
    $mail->Subject = $subject;

// Mail body content
    $bodyContent = $content;
    $mail->Body = $bodyContent;

// Send email
    if (!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    } else {
        header("location: mailsucces.php?id=");
    }
}


?>