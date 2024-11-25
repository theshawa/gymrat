<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login");

require_once "../../../db/models/Complaints.php";

try {
    $type = $_POST['type'] ?? null;
    $description = $_POST['description'] ?? null;
    $isCreatedByTrainer = $_POST['is_created_by_trainer'] ?? 0;

    if (empty($type) || empty($description)) {
        redirect_with_error_alert("All fields are required.", "../make-complaint");
    }

    session_start();
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        redirect_with_error_alert("You must be logged in to make a complaint.", "/rat/login");
    }

    $complaint = new Complaints();

    $complaint->fill([
        'type' => $type,
        'description' => $description,
        'user_id' => $userId,
        'is_created_by_trainer' => $isCreatedByTrainer,
        'created_at' => date("Y-m-d H:i:s"),
    ]);

    $complaint->create();

    redirect_with_success_alert("Complaint submitted successfully.", "../complaints");
} catch (Exception $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "../make-complaint");
}
