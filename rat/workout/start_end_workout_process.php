<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login");

$started = $_SESSION['user']['workout'] ?? false;

if ($started) {
    unset($_SESSION['user']['workout']);
    redirect_with_success_alert("Workout ended successfully.", "../");
} else {
    $_SESSION['user']['workout'] = true;
    redirect_with_success_alert("Workout started successfully.", "./");
}
