<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("method not allowed");
}

session_start();

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
    $otp = $_POST['otp'];
    if ($_SESSION['forgot_password_otp']['otp'] == $otp) {
        // check if OTP is expired(with OTP life span of 5 minutes)
        if (time() - $_SESSION['forgot_password_otp']['created_at'] > 60 * 5) {
            unset($_SESSION['forgot_password_otp']);
            $_SESSION['error'] = "OTP Code Expired";
            header("Location: /rat/login/forgot-password");
            return;
        }
        header("Location: /rat/login/forgot-password/reset-password");
    } else {
        $_SESSION['error'] = "Invalid OTP Code";
        header("Location: /rat/login/forgot-password");
    }
}

function resendOTP()
{
    if (time() - $_SESSION['forgot_password_otp']['created_at'] < 60 * $_SESSION['forgot_password_otp']['creation_attempt']) {
        $waitTime = 60 * $_SESSION['forgot_password_otp']['creation_attempt'] - (time() - $_SESSION['forgot_password_otp']['created_at']);
        $_SESSION['error'] = "Please wait $waitTime seconds before resending OTP";
        header("Location: /rat/login/forgot-password");
        return;
    }
    sendOTP($_SESSION['forgot_password_otp']['email'], $_SESSION['forgot_password_otp']['creation_attempt'] + 1);
}


if (!isset($_POST['action'])) {
    die("no action provided!");
}

switch ($_POST['action']) {
    case 'send':
        sendOTP($_POST['email'], 1);
        break;
    case 'verify':
        verifyOTP();
        break;
    case 'resend':
        resendOTP();
        break;
    default:
        die("Invalid action");
}
