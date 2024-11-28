<?php


require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_not_required_guard_with_role("trainer", "/trainer");



$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);

if ($email !== "johncena@example.com" || $password !== "123456") {
    redirect_with_error_alert("Invalid credentials", "./");
}

$_SESSION["auth"] = [
    'id' => 0,
    'email' => $email,
    'fname' => "John",
    'lname' => "Cena",
    'session_started_at' => time(),
    'activated' => false,
    'role' => "trainer"
];

redirect_with_success_alert("Logged in successfully", "../");
