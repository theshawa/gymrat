<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/login");
}

require_once "../../auth-guards.php";
//auth_not_required_guard("/staff/login");

require_once "../../db/models/Staff.php";

$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);

$user = new Staff();
$user->fill([
    "email" => $email,
]);

try {
    $user->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to login user due to error: " . $e->getMessage(), "./");
}

if (!$user->id) {
    redirect_with_error_alert("User not found", "/staff/login");
}

if (!password_verify($password, $user->password)) {
    redirect_with_error_alert("Invalid password", "/staff/login");
}

$_SESSION["auth"] = [
    'id' => $user->id,
    'email' => $user->email,
    'name' => $user->name,
    'role' => $user->role,
    'session_started_at' => time()
];

switch ($_SESSION["auth"]["role"]) {
    case "admin":
        redirect_with_success_alert("Logged in successfully", "/staff/admin");
        break;

    case "wnmp":
        redirect_with_success_alert("Logged in successfully", "/staff/wnmp");
        break;

    case "eq":
        redirect_with_success_alert("Logged in successfully", "/staff/eq");
        break;

    default:
        redirect_with_error_alert("Logged in successfully but undefined role", "/staff/login");
        break;
}

