<?php
// File path: src/trainer/announcements/delete/delete_announcement_process.php
require_once "../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../../auth-guards.php";
auth_required_guard("trainer", "../../login");

require_once "../../../db/models/Announcement.php";

$id = intval($_POST['id']);

// Get the announcement
$announcement = new Announcement();
$announcement->id = $id;

try {
    $announcement->get_by_id();
    
    // Check if this announcement belongs to the current trainer
    $trainer_id = $_SESSION['auth']['id'];
    if ($announcement->source != $trainer_id) {
        redirect_with_error_alert("You can only delete your own announcements", "../");
    }

    // Delete the announcement
    $announcement->delete();
} catch (Exception $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "../");
}

redirect_with_success_alert("Announcement deleted successfully", "../");