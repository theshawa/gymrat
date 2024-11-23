<?php

session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', './');
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login");

// TODO: Save initial data to database

// update user onboarded status
require_once "../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id'],
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch user due to error: " . $e->getMessage(), "./");
}

$user->onboarded = true;

try {
    $user->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update user due to error: " . $e->getMessage(), "./");
}

redirect_with_success_alert("Welcome to GYMRAT!", "/rat");
