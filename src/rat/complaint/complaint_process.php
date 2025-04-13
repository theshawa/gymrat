<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

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
]);

try {
    $complaint->create();
} catch (PDOException $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./");
}

redirect_with_success_alert("Complaint submitted successfully.", "./");
