<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("trainer", "../login");

require_once "../../db/models/Complaint.php";

$type = htmlspecialchars($_POST['type']);
$description = trim(htmlspecialchars($_POST['description']));

if (empty($description)) {
    redirect_with_error_alert("Description cannot be empty", "./");
}

$userId = $_SESSION['auth']['id'];

$complaint = new Complaint();

$complaint->fill([
    'type' => $type,
    'description' => $description,
    'user_id' => $userId,
    'is_created_by_trainer' => true
]);

try {
    $complaint->create();

    // Send notification to trainer
    require_once "../../notifications/functions.php";

    try {
        notify_trainer(
            $userId,
            "Complaint Submitted",
            "Your complaint has been submitted successfully. You will be notified when an admin reviews it."
        );
    } catch (\Throwable $th) {
        // Continue even if notification fails
    }

} catch (PDOException $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./");
}

require_once "../../notifications/functions.php";

try {
    notify_trainer($_SESSION['auth']['id'], "New complaint submitted", "Your complaint has been submitted successfully. We will review it and get back to you soon.");
} catch (\Throwable $th) {
    redirect_with_info_alert("Complaint submitted, but failed to send notification: " . $th->getMessage(), "./");
}

redirect_with_success_alert("Complaint submitted successfully.", "./");