<?php

session_start();

require_once "../../../../db/models/Announcement.php";
require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/announcements");
}

$announcement = unserialize($_SESSION['announcement']);
$errors = [];

$id = $announcement->id;
$title = htmlspecialchars($_POST['title']);
$message = htmlspecialchars($_POST['message']);
$valid_till = htmlspecialchars($_POST['valid_till']);

// Defaulting to 00:00 if not specified
if (strpos($valid_till, 'T') === false) {
    $valid_till .= 'T00:00';
}
$current_date = new DateTime();

if (empty($title)) $errors[] = "Title is required.";
if (empty($message)) $errors[] = "Message is required.";

if (empty($valid_till)) $errors[] = "Valid till date is required.";
$valid_till_datetime = DateTime::createFromFormat('Y-m-d\TH:i', $valid_till);

if (!$valid_till_datetime) {
    $errors[] = "Valid till date is invalid.";
} elseif ($valid_till_datetime < $current_date) {
    $errors[] = "Valid till date cannot be in the past.";
}


$announcement->title = $title;
$announcement->message = $message;
$announcement->valid_till = new DateTime($valid_till);

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/announcements/edit/index.php?id=$id");
    exit;
}

// Revert Logic
if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    $announcement = new Announcement();
    $announcement->id = $id;
    $announcement->get_by_id();
}

$_SESSION['announcement'] = serialize($announcement);

// Save Logic
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    try {
        $announcement->message = $announcement->message;
        $announcement->update();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to update announcement due to an error: " . $e->getMessage(), "/staff/admin/announcements/edit/index.php?id=$id");
        exit;
    }

    unset($_SESSION['announcement']);

    redirect_with_success_alert("Announcement updated successfully", "/staff/admin/announcements/view/index.php?id=$id");
    exit;
}

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/admin/announcements/edit/index.php?id=$id");
exit;
