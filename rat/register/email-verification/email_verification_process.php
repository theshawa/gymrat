<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "../");
}

if (!isset($_SESSION['customer_registration'])) {
    redirect_with_error_alert("Invalid request", "../");
}

require_once "../../../db/models/CustomerEmailVerificationRequest.php";

$request = new CustomerEmailVerificationRequest();
try {
    $request->get_by_email($_SESSION['customer_registration']['email']);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "../");
}

if (!isset($request->code)) {
    redirect_with_error_alert("Invalid request", "../");
}

if ($_POST['code'] !== $request->code) {
    redirect_with_error_alert("Invalid code!", "..//email-verification");
}

try {
    $request->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete email verification request due to error: " . $e->getMessage(), "../");
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
    redirect_with_error_alert("Failed to upload avatar due to an error: failed to move file from temp", "../");
}

try {
    $customer->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "../");
}

unset($_SESSION['customer_registration']);
redirect_with_success_alert("Registration successful!", "..//onboarding/facts");
