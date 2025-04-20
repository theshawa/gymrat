<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Customer.php";

$customer = unserialize($_SESSION['customer']);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/rats/view/index.php?id=$customer->id");
    exit;
}

$membership_id = htmlspecialchars($_POST['membership_id']);

$customer->membership_plan = $membership_id;
$customer->membership_plan_activated_at = new DateTime();
try {
    $customer->save();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to update membership plan: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$customer->id");
    exit;
}

redirect_with_success_alert("Membership plan successfully updated", "/staff/admin/rats/view/index.php?id=$customer->id");
exit;
?>