<?php
// File path: src/trainer/complaint/edit/edit_complaint_process.php
session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "../");
}

require_once "../../../db/models/Complaint.php";

// Validate and sanitize inputs
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$type = htmlspecialchars($_POST['type']);
$description = trim(htmlspecialchars($_POST['description']));

// Validate complaint ID
if (!$id) {
    redirect_with_error_alert("Invalid complaint ID", "../");
}

// Validate complaint data
if (empty($description)) {
    redirect_with_error_alert("Description cannot be empty", "./?id=$id");
}

// Get the existing complaint
$complaint = new Complaint();
$complaint->id = $id;

try {
    $complaint->get_by_id($id);
    
    // Check if this complaint belongs to the current user
    $user_id = $_SESSION['auth']['id'];
    if ($complaint->user_id != $user_id || $complaint->user_type != $_SESSION['auth']['role']) {
        redirect_with_error_alert("You can only edit your own complaints", "../");
    }
    
    // Check if the complaint was created less than 5 minutes ago
    $current_time = new DateTime();
    $created_time = $complaint->created_at;
    $edit_time_diff = $current_time->getTimestamp() - $created_time->getTimestamp();
    
    if ($edit_time_diff > 300) { // 300 seconds = 5 minutes
        redirect_with_error_alert("Edit time expired. You can only edit complaints within 5 minutes after posting", "../");
    }
    
    // Update the complaint
    $complaint->type = $type;
    $complaint->description = $description;
    
    $complaint->update();
    
    redirect_with_success_alert("Complaint updated successfully", "../");
    
} catch (Exception $e) {
    redirect_with_error_alert("Failed to update complaint: " . $e->getMessage(), "../");
}