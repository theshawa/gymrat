<?php


require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/login");
}

require_once "../../auth-guards.php";
auth_not_required_guard("/rat");

require_once "../../db/models/Customer.php";

$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);

$user = new Customer();
$user->fill([
    "email" => $email,
]);

try {
    $user->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to login user due to error: " . $e->getMessage(), "./");
}

if (!$user->id) {
    redirect_with_error_alert("Invalid email or password", "/rat/login");
}

if (!password_verify($password, $user->password)) {
    redirect_with_error_alert("Invalid email or password", "/rat/login");
}

$_SESSION["auth"] = [
    'id' => $user->id,
    'email' => $user->email,
    'fname' => $user->fname,
    'lname' => $user->lname,
    'session_started_at' => time(),
    'activated' => false,
    'role' => 'rat'
];

if (!$user->membership_plan) {
    header("Location: ./no-subscription");
    exit;
}

if (!$user->onboarded) {
    header("Location: /rat/onboarding/facts");
    exit;
}

$_SESSION["auth"]["activated"] = true;

redirect_with_success_alert("Logged in successfully", "/rat");
