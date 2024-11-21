<?php

require_once "../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/login/forgot-password");
}

session_start();

$action = htmlspecialchars($_POST['action'] ?? null);

function genereateOTP()
{
    return rand(100000, 999999);
}

function sendOTP(string $email, int $creation_attempt)
{
    $otp = genereateOTP();
    echo "Your OTP is: $otp";
    echo "<br>";
    echo "<a href='/rat/login/forgot-password'>Go to OTP verification</a>";

    // TODO: save OTP record on database for future verification(For the sake of time, we will use session)

    $_SESSION['forgot_password_otp'] = [
        'email' => $email,
        'otp' => $otp, // Remove this line in production
        'created_at' => time(),
        'creation_attempt' => $creation_attempt
    ];
}

function verifyOTP()
{
    $otp = htmlspecialchars($_POST['otp']);
    $otp = htmlspecialchars($otp);
    if ($_SESSION['forgot_password_otp']['otp'] == $otp) {
        // check if OTP is expired(with OTP life span of 5 minutes)
        if (time() - $_SESSION['forgot_password_otp']['created_at'] > 60 * 5) {
            unset($_SESSION['forgot_password_otp']);
            redirect_with_error_alert("OTP Code Expired", "/rat/login/forgot-password");
        }
        header("Location: /rat/login/forgot-password/reset-password");
    } else {
        redirect_with_error_alert("Invalid OTP Code", "/rat/login/forgot-password");
    }
}

function resendOTP()
{
    if (time() - $_SESSION['forgot_password_otp']['created_at'] < 60 * $_SESSION['forgot_password_otp']['creation_attempt']) {
        $waitTime = 60 * $_SESSION['forgot_password_otp']['creation_attempt'] - (time() - $_SESSION['forgot_password_otp']['created_at']);
        redirect_with_error_alert("Please wait $waitTime seconds before resending OTP", "/rat/login/forgot-password");
    }
    sendOTP($_SESSION['forgot_password_otp']['email'], $_SESSION['forgot_password_otp']['creation_attempt'] + 1);
}


if (!$action) {
    redirect_with_error_alert("No action provided", "/rat/login/forgot-password");
}

switch ($action) {
    case 'send':
        $email = htmlspecialchars($_POST['email']);
        sendOTP($email, 1);
        break;
    case 'verify':
        verifyOTP();
        break;
    case 'resend':
        resendOTP();
        break;
    default:
        redirect_with_error_alert("Invalid action", "/rat/login/forgot-password");
}
