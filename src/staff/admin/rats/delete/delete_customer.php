<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/rats");
    exit;
}

$customer_id = $_POST['customer_id'];

require_once "../../../../db/models/Customer.php";

$customer = new Customer();
try {
    $customer->id = $customer_id;
    $customer->get_by_id();
    $customer->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete customer due to an error: " . $e->getMessage(), "/staff/admin/rats");
    exit;
}

redirect_with_success_alert("Customer deleted successfully", "/staff/admin/rats");
exit;
