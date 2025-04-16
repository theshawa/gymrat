<?php

session_start();

$order_id = htmlspecialchars($_GET['order_id']);

require_once "../../../alerts/functions.php";

if (empty($order_id)) {
    redirect_with_error_alert("Order ID is required", "../");
    exit;
}

require_once "../../../db/models/MembershipPayment.php";

$payment = new MembershipPayment();
$payment->fill([
    'id' => $order_id,
]);

try {
    $payment->delete();
} catch (Exception $e) {
    redirect_with_error_alert("Payment cancellation failed! Failed to fetch payment due to error: " . $e->getMessage(), "../");
    exit;
}

redirect_with_info_alert("Payment cancelled.", "../");
