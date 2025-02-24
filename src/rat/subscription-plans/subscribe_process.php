<?php

session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', './');
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login", false);

// update user onboarded status
require_once "../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id'],
]);

$plan = htmlspecialchars($_POST['plan']);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch user due to error: " . $e->getMessage(), "./");
}

$user->membership_plan = (int) $plan;
$user->membership_plan_activated_at = new DateTime();

try {
    $user->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update user due to error: " . $e->getMessage(), "./");
}

$_SESSION['auth']['activated'] = true;

redirect_with_success_alert("Plan activated successfully", $user->onboarded ? "/rat" : "/rat/onboarding/facts");
