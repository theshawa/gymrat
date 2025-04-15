<?php

require_once "../../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../../../auth-guards.php";
auth_required_guard("trainer", "../../../login");

// Get form data
$customerId = isset($_POST['customer_id']) ? (int) $_POST['customer_id'] : 0;
$issueType = htmlspecialchars($_POST['issue_type']);
$description = trim(htmlspecialchars($_POST['description']));
$severity = htmlspecialchars($_POST['severity'] ?? 'medium');

// Validate input
if (empty($description)) {
    redirect_with_error_alert("Description cannot be empty", "./index.php?id=" . $customerId);
}

if ($customerId <= 0) {
    redirect_with_error_alert("Invalid customer ID", "../../");
}

$trainerId = $_SESSION['auth']['id'];

// Format the description to include Customer ID and Severity
// Now including the customer ID reference in the description, not in the type
$formattedDescription = "[Customer #$customerId] [Severity: $severity] $description";

// Just use the issue type as is, without customer reference
$formattedIssueType = $issueType;

// Get database connection
require_once "../../../../db/Database.php";
require_once "../../../../db/models/Complaint.php";

try {
    $complaint = new Complaint();
    $complaint->fill([
        'type' => $formattedIssueType,
        'description' => $formattedDescription,
        'user_id' => $trainerId,
        'user_type' => 'trainer'
    ]);

    $complaint->create();

    // Redirect on success
    redirect_with_success_alert("Customer report submitted successfully.", "./index.php?id=" . $customerId);

} catch (PDOException $e) {
    // Redirect on error
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./index.php?id=" . $customerId);
}