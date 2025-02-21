<?php


require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_not_required_guard_with_role("trainer", "/trainer");

$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);


require_once "../../db/models/Trainer.php";
$trainer = new Trainer();
$trainer->fill(['username' => $username]);
try {
    $trainer->get_by_username();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to login user due to error: " . $e->getMessage(), "./");
}

if (!$trainer->id) {
    redirect_with_error_alert("Invalid username or password", "./");
}

if (!password_verify($password, $trainer->password)) {
    redirect_with_error_alert("Invalid username or password", "./");
}

$_SESSION["auth"] = [
    'id' => $trainer->id,
    'username' => $trainer->username,
    'fname' => $trainer->fname,
    'lname' => $trainer->lname,
    'bio' => $trainer->bio,
    'session_started_at' => time(),
    'role' => "trainer"
];

redirect_with_success_alert("Logged in successfully", "../");
