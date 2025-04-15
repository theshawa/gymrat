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

// Get database connection
require_once "../../../../db/Database.php";
$conn = Database::get_conn();

try {
    // Format the description to include Customer ID and Severity
    $formattedDescription = "[Customer ID: $customerId] [Severity: $severity] $description";

    // Use plain issue type without appending customer ID
    $formattedIssueType = $issueType;

    // Insert into complaints table
    $sql = "INSERT INTO complaints 
            (type, description, user_id, is_created_by_trainer) 
            VALUES (:type, :description, :user_id, 1)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'type' => $formattedIssueType,
        'description' => $formattedDescription,
        'user_id' => $trainerId
    ]);

    // Redirect on success
    redirect_with_success_alert("Customer report submitted successfully.", "./index.php?id=" . $customerId);

} catch (PDOException $e) {
    // Redirect on error
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./index.php?id=" . $customerId);
}