<?php

session_start();

require_once "../../../../alerts/functions.php";


if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/membership-plans");
}

$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);
$price = (float)htmlspecialchars($_POST['price']);
$duration = (int)htmlspecialchars($_POST['duration']);

require_once "../../../../db/models/MembershipPlan.php";

$membershipPlan = new MembershipPlan();
$membershipPlan->fill([
    'name' => $name,
    'description' => $description,
    'price' => $price,
    'duration' => $duration,
]);
try {
    $membershipPlan->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to create membership plan due to an error: Membership plan with the same name already exists", "/staff/admin/membership-plans/new");
    }
    redirect_with_error_alert("Failed to create membership plan due to an error: " . $e->getMessage(), "/staff/admin/membership-plans/new");
}

redirect_with_success_alert("Membership plan created successfully", "/staff/admin/membership-plans");
