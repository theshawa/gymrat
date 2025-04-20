<?php

session_start();

require_once "../../../../db/models/Customer.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/trainers");
}

$id = htmlspecialchars($_POST['trainer_id']);
$customer_id = htmlspecialchars($_POST['customer_id']);

$customer = new Customer();
try {
    $customer->id = $customer_id;
    $customer->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to retrieve customer: " . $e->getMessage(), "/staff/admin/trainers/assignments/index.php?id=$id");
    exit;
}

try {
    $customer->trainer = null;
    $customer->save();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to remove trainer from customer: " . $e->getMessage(), "/staff/admin/trainers/assignments/index.php?id=$id");
    exit;
}

redirect_with_success_alert("Customer removal successful", "/staff/admin/trainers/assignments/index.php?id=$id");
exit;
