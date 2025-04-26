<?php
// File path: src/trainer/announcements/edit/edit_announcement_process.php
require_once "../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../../auth-guards.php";
auth_required_guard("trainer", "../../login");

require_once "../../../db/models/Announcement.php";

$id = intval($_POST['id']);
$title = htmlspecialchars($_POST['title']);
$message = trim(htmlspecialchars($_POST['message']));
$valid_till = trim(htmlspecialchars($_POST['valid_till']));

// Set the time to end of day (23:59:59)
$valid_till_date = new DateTime($valid_till);
$valid_till_date->setTime(23, 59, 59);
$valid_till = $valid_till_date->format('Y-m-d H:i:s');

// Get the announcement
$announcement = new Announcement();
$announcement->id = $id;

try {
    $announcement->get_by_id();
    
    // Check if this announcement belongs to the current trainer
    $trainer_id = $_SESSION['auth']['id'];
    if ($announcement->source != $trainer_id) {
        redirect_with_error_alert("You can only edit your own announcements", "../");
    }
    
    // Check if the announcement was created less than 5 minutes ago
    $current_time = new DateTime();
    $edit_time_diff = $current_time->getTimestamp() - $announcement->created_at->getTimestamp();
    
    if ($edit_time_diff > 300) { // 300 seconds = 5 minutes
        redirect_with_error_alert("You can only edit announcements within 5 minutes after posting", "../");
    }

    // Update announcement
    $announcement->fill([
        'id' => $id,
        'title' => $title,
        'message' => $message,
        'valid_till' => $valid_till,
        'to_all' => $announcement->to_all,
        'source' => $announcement->source,
        'created_at' => $announcement->created_at->format('Y-m-d H:i:s')
    ]);

    $announcement->update();
} catch (Exception $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./");
}

redirect_with_success_alert("Announcement updated successfully", "../");