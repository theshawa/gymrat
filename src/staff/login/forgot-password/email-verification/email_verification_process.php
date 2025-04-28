<?php

session_start();

// print_r($_SESSION['staff_password_reset']);

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

if (!isset($_SESSION['staff_password_reset'])) {
    die("Session expired. Please try again.");
}

require_once "../../../../db/models/StaffPasswordResetRequest.php";

$request = new StaffPasswordResetRequest();
$request->fill([
    'email' => $_SESSION['staff_password_reset']['email'],
]);
try {
    $request->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to verify email due to error: " . $e->getMessage(), "../");
    exit;
}

// print_r($request);

if (!$request->code) {
    redirect_with_error_alert("Failed to Fetch code!", "../");
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
    redirect_with_error_alert("Failed to delete email verification request due to error: " . $e->getMessage(), "../");
    exit;
}

$_SESSION['staff_password_reset']['verified'] = true;

redirect_with_success_alert("Verification successful!", "../reset-password/index.php");
exit;