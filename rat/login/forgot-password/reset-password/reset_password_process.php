<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "../");
}

if (!isset($_SESSION['customer_password_reset'])) {
    redirect_with_error_alert("Invalid request", "../");
}

if (!isset($_SESSION['customer_password_reset']['verified'])) {
    redirect_with_error_alert("Please verify email", "../");
}

$password = htmlspecialchars($_POST['password']);
$cpassword = htmlspecialchars($_POST['cpassword']);

if ($password !== $cpassword) {
    redirect_with_error_alert("Passwords do not match", "./");
}

require_once "../../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'email' => $_SESSION['customer_password_reset']['email'],
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);

try {
    $user->update_password();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to reset password due to error: " . $e->getMessage(), "./");
}

unset($_SESSION['customer_password_reset']);

redirect_with_success_alert("Password reset successful!", "/rat/login");
