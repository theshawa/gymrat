<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/trainers");
    exit;
}

$trainer_id = $_POST['trainer_id'];

require_once "../../../../db/models/Trainer.php";

$trainer = new Trainer();
try {
    $trainer->id = $trainer_id;
    $trainer->get_by_id();
    $trainer->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete trainer due to an error: " . $e->getMessage(), "/staff/admin/trainers");
    exit;
}

redirect_with_success_alert("Trainer deleted successfully", "/staff/admin/trainers");
exit;
