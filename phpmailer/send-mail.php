<?php

require __DIR__ . '/lib/src/PHPMailer.php';
require __DIR__ . '/lib/src/SMTP.php';
require __DIR__ . '/lib/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

function send_mail(array $to, string $subject, string $body)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();  // Use SMTP
    $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send through
    $mail->SMTPAuth = true;  // Enable SMTP authentication
    $mail->Username = '2022cs136@stu.ucsc.cmb.ac.lk';  // SMTP username
    $mail->Password = 'ubkw xiwa nhvt yfzt';  // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
    $mail->Port = 587;  // TCP port to connect to

    //Recipients
    $mail->setFrom('mrclocktd@gmail.com', 'GYMRAT');
    $mail->addAddress($to['email'], $to['name']);  // Add a recipient

    // Content
    $mail->isHTML(true);  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;

    // Send the email
    $mail->send();
}
