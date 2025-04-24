<?php


require_once "../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

require_once "../../../auth-guards.php";
auth_not_required_guard("rat", "/rat/login");

$email = htmlspecialchars($_POST['email']);

require_once "../../../db/models/CustomerPasswordResetRequest.php";

$request = new CustomerPasswordResetRequest();
$request->fill([
    'email' => $email
]);
try {
    $request->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create password reset request user due to error: " . $e->getMessage(), "./");
    exit;
}

if ($request->code) {
    redirect_with_error_alert("Password reset request already sent. Please check your email.", "./");
    exit;
}

$request->fill([
    'email' => $email,
    'code' => strval(rand(100000, 999999)),
]);

try {
    $request->create();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to create password reset request due to error: " . $e->getMessage(), "./");
    exit;
}

require_once "../../../send_email.php";

use PHPMailer\PHPMailer\Exception;

try {
    send_email(
        [
            'email' => $request->email,
            'name' => $request->email
        ],
        'Forgot Password Verification Code',
        'Your code is <b>' . $request->code . '</b>.'
    );
} catch (Exception $e) {
    redirect_with_error_alert("Failed to send email due to error: " . $e->getMessage(), "./");
    exit;
}

$_SESSION['customer_password_reset'] = [
    'email' => $email,
];

redirect_with_info_alert("Password reset request sent. Please check your email", "./email-verification");
