<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Customer.php";

$customer = new Customer();
$customer = unserialize($_SESSION['customer']);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/rats/view/index.php?id=$customer->id");
    exit;
}

$trainer_id = htmlspecialchars($_POST['trainer_id']);

$customer->trainer = $trainer_id;
try {
    $customer->save();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to assign trainer: " . $e->getMessage(), "/staff/admin/rats/view/index.php?id=$customer->id");
    exit;
}

redirect_with_success_alert("Trainer succefully assigned", "/staff/admin/rats/view/index.php?id=$customer->id");
exit;
?>
