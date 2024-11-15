<?php

session_start();

require_once "../../../alerts/functions.php";

if (!isset($_SESSION['customer_registration'])) {
    redirect_with_error_alert("Invalid request", "/rat/register");
}

require_once "../../../db/models/CustomerEmailVerificationRequest.php";

$request = new CustomerEmailVerificationRequest();

try {
    $request->get_by_email($_SESSION['customer_registration']['email']);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "/rat/register");
}

if (!isset($request->code)) {
    redirect_with_error_alert("Invalid request", "/rat/register");
}

require_once "../../constants.php";
$period_from_creation = (new DateTime())->format("U") - (int)$request->created_at->format("U");

if (
    $request->creation_attempt >= $CUSTOMER_EMAIL_VERIFICATION_REQUEST_MAXIMUM_ATTEMPTS
    && $period_from_creation < $CUSTOMER_EMAIL_VERIFICATION_REQUEST_TIMEOUT
) {
    $waitDuration = $CUSTOMER_EMAIL_VERIFICATION_REQUEST_TIMEOUT - $period_from_creation;
    redirect_with_error_alert("You have reached the maximum number of attempts. Please wait for $waitDuration seconds before trying again", "/rat/register/email-verification");
}

try {
    $request->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete email verification request due to error: " . $e->getMessage(), "/rat/register");
}

$request->fill([
    'email' => $_SESSION['customer_registration']['email'],
    'code' => strval(rand(100000, 999999)),
    'creation_attempt' => $request->creation_attempt + 1,
]);

try {
    $request->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create email verification request due to error: " . $e->getMessage(), "/rat/register");
}

// TODO: send email

redirect_with_info_alert("Email verification request sent. Please check your email", "/rat/register/email-verification");
