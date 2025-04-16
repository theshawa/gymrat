<?php

session_start();

require_once "../../../../alerts/functions.php";

if (!isset($_SESSION['customer_password_reset'])) {
    die("Invalid request");
}

require_once "../../../../db/models/CustomerPasswordResetRequest.php";

$request = new CustomerPasswordResetRequest();
$request->fill([
    'email' => $_SESSION['customer_password_reset']['email'],
]);
try {
    $request->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to verify email due to error: " . $e->getMessage(), "../");
    exit;
}

if (!$request->code) {
    redirect_with_error_alert("Invalid request", "../");
    exit;
}

$period_from_creation = (new DateTime())->format("U") - (int)$request->created_at->format("U");


require_once "../../../../constants.php";
if (
    $request->creation_attempt >= CUSTOMER_EMAIL_VERIFICATION_REQUEST_MAXIMUM_ATTEMPTS
    && $period_from_creation < CUSTOMER_EMAIL_VERIFICATION_REQUEST_TIMEOUT
) {
    $waitDuration = CUSTOMER_EMAIL_VERIFICATION_REQUEST_TIMEOUT - $period_from_creation;
    redirect_with_error_alert("You have reached the maximum number of attempts. Please wait for $waitDuration seconds before trying again", "..//email-verification");
    exit;
}

try {
    $request->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete password reset request due to error: " . $e->getMessage(), "../");
    exit;
}

$request->fill([
    'email' => $_SESSION['customer_password_reset']['email'],
    'code' => strval(rand(100000, 999999)),
    'creation_attempt' => $request->creation_attempt + 1,
]);

try {
    $request->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create password reset request due to error: " . $e->getMessage(), "../");
    exit;
}

// TODO: send email

redirect_with_info_alert("Password reset request sent. Please check your email", "./");
