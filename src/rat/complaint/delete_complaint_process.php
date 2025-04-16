<?php

require_once "../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

$id = htmlspecialchars($_POST['id']);
require_once "../../db/models/Complaint.php";
$complaint = new Complaint();
$complaint->fill(
    [
        'id' => $id,
    ]
);

try {
    $complaint->delete();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to delete complaint due to an error:" . $e->getMessage(), "./");
    exit;
}

redirect_with_success_alert("Complaint deleted successfully.", "./");
