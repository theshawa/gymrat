<?php

session_start();

require_once "../../../../alerts/functions.php";

$customer_id = htmlspecialchars($_POST['customer_id']);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/rats/view/index.php?id=$customer_id");
    exit;
}

$trainer_id = htmlspecialchars($_POST['trainer_id']);

redirect_with_success_alert("Confirm Trainer to complete process", "/staff/admin/rats/view/index.php?id=$customer_id&confirm=$trainer_id");
exit;
?>
