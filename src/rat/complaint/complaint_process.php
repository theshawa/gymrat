<?php

session_start();

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

require_once "../../db/models/Complaint.php";

$type = htmlspecialchars($_POST['type']);
$description = trim(htmlspecialchars($_POST['description']));

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
    exit;
}

redirect_with_success_alert("Complaint submitted successfully.", "./");
