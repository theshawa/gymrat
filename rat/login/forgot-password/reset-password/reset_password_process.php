<?php

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/login");
}

session_start();

$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];

if ($password !== $repeat_password) {
    redirect_with_alert("Passwords do not match. Please start process again.", "/rat/login/forgot-password");
}

// TODO: Reset password logic here...

redirect_with_success_alert("Password reset successfully", "/rat/login");
