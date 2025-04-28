<?php
require_once "../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login")) exit;

require_once "../../../../db/models/Complaint.php";
require_once "../../../../alerts/functions.php";

// Verify this is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Invalid request method", "./index.php?id=" . ($_POST['customer_id'] ?? 0));
    exit;
}

// Get report ID and customer ID
$reportId = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
$customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

if (!$reportId || !$customerId) {
    redirect_with_error_alert("Missing report ID or customer ID", "./index.php?id=" . $customerId);
    exit;
}

// Get trainer ID from session
$trainerId = $_SESSION['auth']['id'] ?? 0;

try {
    // Load the report (complaint)
    $complaint = new Complaint();
    $complaint->id = $reportId;
    if (!$complaint->get_by_id()) {
        redirect_with_error_alert("Report not found", "./index.php?id=" . $customerId);
        exit;
    }
    
    // Verify ownership - only allow deletion of reports created by this trainer
    if ($complaint->user_id != $trainerId || $complaint->user_type != 'trainer') {
        redirect_with_error_alert("You don't have permission to delete this report", "./index.php?id=" . $customerId);
        exit;
    }
    
    // Check if the report is within the 5-minute edit window
    $now = new DateTime();
    $created = $complaint->created_at;
    $interval = $created->diff($now);
    $totalMinutes = $interval->i + ($interval->h * 60) + ($interval->days * 24 * 60);
    
    if ($totalMinutes >= 5) {
        redirect_with_error_alert("This report can no longer be deleted (5-minute window has passed)", "./index.php?id=" . $customerId);
        exit;
    }
    
    // Delete the report
    if ($complaint->delete()) {
        redirect_with_success_alert("Report deleted successfully", "./index.php?id=" . $customerId);
    } else {
        redirect_with_error_alert("Failed to delete report", "./index.php?id=" . $customerId);
    }
    
} catch (Exception $e) {
    redirect_with_error_alert("Error: " . $e->getMessage(), "./index.php?id=" . $customerId);
    exit;
}