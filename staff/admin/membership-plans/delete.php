<?php

session_start();

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    die("method not allowed");
}

$id = $_POST['id'];

require_once "../../../db/models/MembershipPlan.php";

require_once "../../../alerts/functions.php";


$membershipPlan = new MembershipPlan();
$membershipPlan->fill([
    'id' => $id,
]);
try {
    $membershipPlan->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete membership plan due to an error: " . $e->getMessage(), "/staff/admin/membership-plans");
}

redirect_with_success_alert("Membership plan deleted successfully", "/staff/admin/membership-plans");
