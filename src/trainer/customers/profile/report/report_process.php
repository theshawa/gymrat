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

$trainerId = $_SESSION['auth']['id'];

// Get database connection
require_once "../../../../db/Database.php";
$conn = Database::get_conn();

try {
    // build description object
    $formatted = [
        'type' => 'CUSTOMER REPORT',
        'customer_id' => $customerId,
        'severity' => $severity,
        'description' => $description,
    ];

    require_once "../../../../db/models/Complaint.php";
    $complaint = new Complaint();
    $complaint->fill(
        [
            'user_id' => $trainerId,
            'type' => $issueType,
            'description' => json_encode($formatted),
            'user_type' => $_SESSION['auth']['role'],
        ]
    );

    $complaint->create();
    // Redirect on success
    redirect_with_success_alert("Customer report submitted successfully.", "./index.php?id=" . $customerId);
} catch (PDOException $e) {
    // Redirect on error
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./index.php?id=" . $customerId);
}
