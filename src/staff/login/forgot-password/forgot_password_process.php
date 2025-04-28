<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/login/forgot-password");
    exit;
}

$email = htmlspecialchars($_POST['email']);

require_once "../../../db/models/StaffPasswordResetRequest.php";
require_once "../../../db/models/Staff.php";

// $staff = new Staff();
// $staff->email = $email;
// try {
//     if (!$staff->get_by_email()) {
//         redirect_with_error_alert("Staff member not found", "/staff/login/forgot-password");
//         exit;
//     }
// } catch (PDOException $e) {
//     redirect_with_error_alert("Failed to fetch staff due to error: " . $e->getMessage(), "/staff/login/forgot-password");
//     exit;
// }

$request = new StaffPasswordResetRequest();
$request->fill([
    'email' => $email
]);
try {
    $request->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create password reset request due to error: " . $e->getMessage(), "/staff/login/forgot-password");
    exit;
}

if ($request->code) {
    $_SESSION['staff_password_reset'] = [
        'email' => $email,
    ];
    redirect_with_error_alert("Password reset request already sent. Please check your email.", "./email-verification");
    exit;
}

$request->fill([
    'email' => $email,
    'code' => strval(rand(100000, 999999)),
]);

try {
    $request->create();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to create password reset request due to error: " . $e->getMessage(), "/staff/login/forgot-password");
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
    redirect_with_error_alert("Failed to send email due to error: " . $e->getMessage(), "/staff/login/forgot-password");
    exit;
}

$_SESSION['staff_password_reset'] = [
    'email' => $email,
];

redirect_with_success_alert("Password reset request sent. Please check your email", "./email-verification");
exit;