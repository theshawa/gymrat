<?php
// File path: src/trainer/customers/profile/delete-log/delete_process.php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../../db/models/TrainerLogRecord.php";
require_once "../../../../alerts/functions.php";

// Get log ID and customer ID from POST
$logId = isset($_POST['log_id']) ? intval($_POST['log_id']) : 0;
$customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

// If IDs are missing, redirect back
if (!$logId || !$customerId) {
    redirect_with_error_alert("Invalid log record ID", "../add-log/?id=$customerId");
}

// Get the log record
$logRecord = new TrainerLogRecord();
$logRecord->id = $logId;

try {
    $logRecord->get_by_id();
    
    // Check if this log belongs to the current trainer
    $trainer_id = $_SESSION['auth']['id'];
    if ($logRecord->trainer_id != $trainer_id) {
        redirect_with_error_alert("You can only delete your own log records", "../add-log/?id=$customerId");
    }
    
    // Double check the customer ID
    if ($logRecord->customer_id != $customerId) {
        redirect_with_error_alert("Invalid log record for this customer", "../add-log/?id=$customerId");
    }
    
    // Delete the log record
    $logRecord->delete();
    
    // Redirect with success message
    redirect_with_success_alert("Log record deleted successfully", "../add-log/?id=$customerId");
    
} catch (Exception $e) {
    redirect_with_error_alert("Error deleting log record: " . $e->getMessage(), "../add-log/?id=$customerId");
}