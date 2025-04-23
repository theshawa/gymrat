<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

if (!isset($_SESSION['customer_password_reset'])) {
    die("Session expired. Please try again.");
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
    redirect_with_error_alert("Invalid code!", "../");
    exit;
}

$code = htmlspecialchars($_POST['code']);

if ($code !== $request->code) {
    redirect_with_error_alert("Invalid code!", "../");
    exit;
}

try {
    $request->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete password reset request due to error: " . $e->getMessage(), "../");
    exit;
}

$_SESSION['customer_password_reset']['verified'] = true;

redirect_with_success_alert("Verification successful!", "../reset-password");
