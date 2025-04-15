<?php
// This file should be placed in the admin section
// /admin/complaints/admin_reply_complaint.php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("admin", "../login");

$complaintId = isset($_POST['complaint_id']) ? (int) $_POST['complaint_id'] : 0;
$adminReply = trim(htmlspecialchars($_POST['admin_reply']));
$status = $_POST['status'] ?? 'reviewed';

if (empty($adminReply)) {
    redirect_with_error_alert("Reply cannot be empty", "./view_complaint.php?id=" . $complaintId);
}

if ($complaintId <= 0) {
    redirect_with_error_alert("Invalid complaint ID", "./");
}

require_once "../../db/Database.php";
require_once "../../db/models/Complaint.php";

// Get the complaint data first to find the trainer ID
$conn = Database::get_conn();
$trainerInfo = null;

try {
    $sql = "SELECT user_id, is_created_by_trainer FROM complaints WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $complaintId);
    $stmt->execute();

    $complaintData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$complaintData) {
        redirect_with_error_alert("Complaint not found", "./");
    }

    // Only proceed if this was created by a trainer
    if (!$complaintData['is_created_by_trainer']) {
        redirect_with_error_alert("This is not a trainer complaint", "./view_complaint.php?id=" . $complaintId);
    }

    $trainerId = $complaintData['user_id'];

    // Update the complaint with admin reply
    $updateSql = "UPDATE complaints SET 
                  admin_reply = :admin_reply, 
                  status = :status, 
                  replied_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";

    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->execute([
        'admin_reply' => $adminReply,
        'status' => $status,
        'id' => $complaintId
    ]);

    // Send notification to trainer
    require_once "../../notifications/functions.php";

    try {
        // Get complaint type
        $typeSql = "SELECT type FROM complaints WHERE id = :id LIMIT 1";
        $typeStmt = $conn->prepare($typeSql);
        $typeStmt->bindValue(':id', $complaintId);
        $typeStmt->execute();
        $complaintType = $typeStmt->fetchColumn();

        // Clean customer ID from type if present
        $complaintType = preg_replace('/\s*\[Customer:\d+\]\s*/', '', $complaintType);

        notify_trainer(
            $trainerId,
            "Complaint Reviewed",
            "Your complaint about '$complaintType' has been reviewed by an admin. Check your complaint history to see the response."
        );
    } catch (\Throwable $th) {
        // Continue even if notification fails
        redirect_with_info_alert(
            "Complaint updated successfully, but notification to trainer failed: " . $th->getMessage(),
            "./view_complaint.php?id=" . $complaintId
        );
    }

} catch (PDOException $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./view_complaint.php?id=" . $complaintId);
}

redirect_with_success_alert("Complaint reply sent successfully. The trainer has been notified.", "./view_complaint.php?id=" . $complaintId);