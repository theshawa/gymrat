<?php

session_start();

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

$complaint = new Complaint();
$complaint->fill(
    [
        'user_id' => $_SESSION['auth']['id'],
        'type' => $type,
        'description' => $description,
        'user_type' => $_SESSION['auth']['role'],
    ]
);

try {
    $complaint->create();
} catch (PDOException $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./");
}

redirect_with_success_alert("Complaint submitted successfully.", "./");
