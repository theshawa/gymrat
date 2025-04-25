<?php

session_start();

require_once "../../../../db/models/Announcement.php";
require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/announcements");
    exit;
}

$announcement = unserialize($_SESSION['announcement']);

try {
    $announcement->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete announcement due to an error: " . $e->getMessage(), "/staff/admin/announcements/edit/index.php?id=$id");
    exit;
}

unset($_SESSION['announcement']);

redirect_with_success_alert("Announcement deleted successfully", "/staff/admin/announcements/index.php?");
exit;