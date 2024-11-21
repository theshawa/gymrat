<?php

session_start();

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    die("method not allowed");
}

$id = htmlspecialchars($_POST['id']);
$status = htmlspecialchars($_POST['status']);

require_once "../../../db/models/MembershipPlan.php";

require_once "../../../alerts/functions.php";

$membershipPlan = new MembershipPlan();
try {
    $membershipPlan->get_by_id($id);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to lock/unlock membership plan due to an error: " . $e->getMessage(), "/staff/admin/membership-plans");
}
$membershipPlan->is_locked = (int) $status;
try {
    $membershipPlan->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to change locked status of membership plan due to an error: " . $e->getMessage(), "/staff/admin/membership-plans");
}

redirect_with_success_alert("Membership plan " . ($status == 1 ? "locked" : "unlocked") . " successfully", "/staff/admin/membership-plans");
