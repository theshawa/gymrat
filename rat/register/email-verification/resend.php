<?php

session_start();

if (!isset($_SESSION['customer_registration'])) {
    die("invalid request");
}

require_once "../../../db/models/CustomerEmailVerificationRequest.php";

$request = new CustomerEmailVerificationRequest();
$request->get_by_email($_SESSION['customer_registration']['email']);

if (!isset($request->code)) {
    die("invalid request");
}

require_once "../../constants.php";
$period_from_creation = (new DateTime())->format("U") - (int)$request->created_at->format("U");

if (
    $request->creation_attempt >= $CUSTOMER_EMAIL_VERIFICATION_REQUEST_MAXIMUM_ATTEMPTS
    && $period_from_creation < $CUSTOMER_EMAIL_VERIFICATION_REQUEST_TIMEOUT
) {
    $waitDuration = $CUSTOMER_EMAIL_VERIFICATION_REQUEST_TIMEOUT - $period_from_creation;
    $_SESSION['alert'] = "You have reached the maximum number of attempts. Please wait for $waitDuration seconds before trying again";
    header("Location: ../email-verification");
    exit();
}

$request->delete();

$request->fill([
    'email' => $_SESSION['customer_registration']['email'],
    'code' => strval(rand(100000, 999999)),
    'creation_attempt' => $request->creation_attempt + 1,
]);

$request->create();

// TODO: send email

$_SESSION['alert'] = "Email verification code resent successfully";
header("Location: ../email-verification");
