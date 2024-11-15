<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/register");
}

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

if ($_POST['code'] !== $request->code) {
    redirect_with_error_alert("Invalid code!", "/rat/register/email-verification");
}

try {
    $request->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete email verification request due to error: " . $e->getMessage(), "/rat/register");
}

// create customer

require_once "../../../db/models/Customer.php";

$customer = new Customer();
$customer->fill([
    'fname' => $_SESSION['customer_registration']['fname'],
    'lname' => $_SESSION['customer_registration']['lname'],
    'email' => $_SESSION['customer_registration']['email'],
    'phone' => $_SESSION['customer_registration']['phone'],
    'password' => $_SESSION['customer_registration']['password'],
    'avatar' => $_SESSION['customer_registration']['avatar'],
]);

// upload from temp folder to customer-avatars
require_once "../../../uploads.php";
$customer->avatar = $customer->avatar ? ltrim($customer->avatar, "tmp/") : null;
if (!move_from_temp($customer->avatar)) {
    redirect_with_error_alert("Failed to upload avatar due to an error: failed to move file from temp", "/rat/register");
}

try {
    $customer->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "/rat/register");
}

unset($_SESSION['customer_registration']);
redirect_with_success_alert("Registration successful!", "/rat/register/onboarding/facts");
