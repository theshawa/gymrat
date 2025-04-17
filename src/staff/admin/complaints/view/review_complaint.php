<?php

session_start();

require_once "../../../../db/models/Complaint.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../notifications/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
    exit;
}

$id = htmlspecialchars($_POST['id']);
$review_message = htmlspecialchars($_POST['review_message']);

if (empty($review_message)) {
    redirect_with_error_alert("No review message is given as response", "/staff/admin/complaints/view/index.php?id=" . $id);
    exit;
}

$complaint = new Complaint();
try {
    $complaint->get_by_id($id);

    $complaint->review_message = $review_message;
    $complaint->reviewed_at = new DateTime();

    $complaint->review();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to review complaint due to an error: " . $e->getMessage(), "/staff/admin/complaints/view/index.php?id=" . $id);
    exit;
}


try {
    if ($complaint->user_type === "trainer") {
        notify_trainer($complaint->user_id, "Complaint Reviewed", "The complaint you have created about " . $complaint->type . " has been reviewed", "admin" );
    } 
    if ($complaint->user_type === "rat") {
        notify_rat($complaint->user_id, "Complaint Reviewed", "The complaint you have created about " . $complaint->type . " has been reviewed", "admin" );
    }
    
} catch (\Throwable $th) {
    $_SESSION['error'] = "Failed to notify user of complaint review: " . $th->getMessage();
}


redirect_with_success_alert("Complaint review confirmed successfully", "/staff/admin/complaints/index.php?filter=1");
exit;
?>