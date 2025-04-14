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
$request->fill([
    'email' => $_SESSION['customer_registration']['email'],
]);
try {
    $request->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "../");
}

if (!$request->code) {
    redirect_with_error_alert("Invalid request", "../");
}

$code = htmlspecialchars($_POST['code']);

if ($code !== $request->code) {
    redirect_with_error_alert("Invalid code!", "../");
}

try {
    $request->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete email verification request due to error: " . $e->getMessage(), "../");
}

// create customer

require_once "../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'fname' => $_SESSION['customer_registration']['fname'],
    'lname' => $_SESSION['customer_registration']['lname'],
    'email' => $_SESSION['customer_registration']['email'],
    'phone' => $_SESSION['customer_registration']['phone'],
    'password' => $_SESSION['customer_registration']['password'],
    'avatar' => $_SESSION['customer_registration']['avatar'],
]);

// upload from temp folder to customer-avatars
require_once "../../../uploads.php";
$user->avatar = $user->avatar ? ltrim($user->avatar, "tmp/") : null;
if ($user->avatar && !move_from_temp($user->avatar)) {
    redirect_with_error_alert("Failed to upload avatar due to an error: failed to move file from temp", "../");
}

try {
    $user->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "../");
}

unset($_SESSION['customer_registration']);

require_once "../../../notifications/functions.php";
try {
    notify_rat($user->id, "Welcome to GYMRAT", "Thank you for registering with us. We hope you have a great experience!");
} catch (\Throwable $th) {
    redirect_with_info_alert("Registration successful, but failed to send notification: " . $th->getMessage(), "/rat/login");
}

redirect_with_success_alert("Registration successful!", "/rat/login");
