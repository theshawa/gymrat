<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', '../');
}

require_once "../../../auth-guards.php";
auth_required_guard("/rat/login");

$current_password = htmlspecialchars($_POST["current_password"]);
$password = htmlspecialchars($_POST["password"]);
$cpassword = htmlspecialchars($_POST["cpassword"]);

if ($password !== $cpassword) {
    redirect_with_error_alert("Passwords do not match", "./");
}

require_once "../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to get user due to error: " . $e->getMessage(), "./");
}

if (!password_verify($current_password, $user->password)) {
    redirect_with_error_alert("Invalid current password", "./");
}

if ($current_password === $password) {
    redirect_with_error_alert("New password cannot be the same as the current password", "./");
}

$user->password = password_hash($password, PASSWORD_DEFAULT);

try {
    $user->update();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update password due to error: " . $e->getMessage(), "./");
}

redirect_with_success_alert("Password updated successfully", "../");
