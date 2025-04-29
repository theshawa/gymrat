<?php
// File path: src/trainer/customers/profile/report/edit/edit_report_process.php
require_once "../../../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "index.php");
}

require_once "../../../../../auth-guards.php";
auth_required_guard("trainer", "/trainer/login");

require_once "../../../../../db/models/Complaint.php";

// Get form data
$reportId = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
$customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
$issueType = isset($_POST['issue_type']) ? htmlspecialchars($_POST['issue_type']) : '';
$description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : '';
$severity = isset($_POST['severity']) ? htmlspecialchars($_POST['severity']) : 'medium';

// Validate input
if (!$reportId || !$customerId || !$issueType || !$description) {
    redirect_with_error_alert("All fields are required", "index.php?id={$reportId}&customer_id={$customerId}");
}

// Get the report
$complaint = new Complaint();
$complaint->id = $reportId;

try {
    $complaint->get_by_id($reportId);
    if (!$complaint->id) {
        redirect_with_error_alert("Report not found", "../index.php?id={$customerId}");
    }
    
    // Check if this report belongs to the current trainer
    $trainerId = $_SESSION['auth']['id'];
    if ($complaint->user_id != $trainerId || $complaint->user_type != 'trainer') {
        redirect_with_error_alert("You can only edit your own reports", "../index.php?id={$customerId}");
    }
    
    // Check if the report was created less than 5 minutes ago
    $current_time = new DateTime();
    $edit_time_diff = $current_time->getTimestamp() - $complaint->created_at->getTimestamp();
    
    if ($edit_time_diff > 300) { // 300 seconds = 5 minutes
        redirect_with_error_alert("You can only edit reports within 5 minutes after posting", "../index.php?id={$customerId}");
    }
    
    // Parse and update the JSON data
    $reportData = json_decode($complaint->description, true);
    if (!$reportData || !isset($reportData['type']) || $reportData['type'] !== 'CUSTOMER REPORT') {
        redirect_with_error_alert("Invalid report format", "../index.php?id={$customerId}");
    }
    
    // Update the report data
    $reportData['severity'] = $severity;
    $reportData['description'] = $description;
    
    // Update the complaint
    $complaint->type = $issueType;
    $complaint->description = json_encode($reportData);
    
    $complaint->update();
    redirect_with_success_alert("Report updated successfully", "../index.php?id={$customerId}");
    
} catch (Exception $e) {
    redirect_with_error_alert("Error updating report: " . $e->getMessage(), "index.php?id={$reportId}&customer_id={$customerId}");
}