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
    $complaint->get_by_id($id);
} catch (\Throwable $th) {
    redirect_with_error_alert("Failed to load complaint due to an error:" . $th->getMessage(), "./");
}

$now = new DateTime();
$is_deletable = $complaint->created_at->diff($now)->i < 5;

if (!$is_deletable) {
    redirect_with_error_alert("You can only delete your complaint within 5 minutes of submission.", "./");
    exit;
}

try {
    $complaint->delete();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to delete complaint due to an error:" . $e->getMessage(), "./");
    exit;
}

redirect_with_success_alert("Complaint deleted successfully.", "./");
