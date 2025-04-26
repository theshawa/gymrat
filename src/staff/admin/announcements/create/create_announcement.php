<?php

session_start();

require_once "../../../../db/models/Announcement.php";
require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/announcements");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    unset($_SESSION['announcement']);
    redirect_with_success_alert("Changes reverted successfully", "/staff/admin/announcements/create/index.php");
    exit;
}

$title = htmlspecialchars($_POST['title']);
$message = htmlspecialchars($_POST['message']);
$valid_till = htmlspecialchars($_POST['valid_till']);

// Defaulting to 00:00 if not specified
if (strpos($valid_till, 'T') === false) {
    $valid_till .= 'T00:00';
}


$to_all = htmlspecialchars($_POST['send_to']);
$current_date = new DateTime();


$announcement = unserialize($_SESSION['announcement']);

$errors = [];
if (empty($title)) $errors[] = "Title is required.";
if (empty($message)) $errors[] = "Message is required.";

// save title and message since they pass validation
$announcement->title = $title;
$announcement->message = $message;
$_SESSION['announcement'] = serialize($announcement);



if (empty($valid_till)) $errors[] = "Valid till date is required.";
$valid_till_datetime = DateTime::createFromFormat('Y-m-d\TH:i', $valid_till);

if (!$valid_till_datetime) {
    $errors[] = "Valid till date is invalid.";
} elseif ($valid_till_datetime < $current_date) {
    $errors[] = "Valid till date cannot be in the past.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/announcements/create/index.php");
    exit;
}

$announcement->source = "admin";
$announcement->to_all = $to_all;
$announcement->valid_till = new DateTime($valid_till);

$_SESSION['announcement'] = serialize($announcement);


if (isset($_POST['action']) && $_POST['action'] === 'create') {
    try {
        $announcement->create();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to create announcement due to an error: " . $e->getMessage(), "/staff/admin/announcements/create/index.php");
        exit;
    }

    unset($_SESSION['announcement']);
    redirect_with_success_alert("Announcement created successfully", "/staff/admin/announcements");
    exit;
}

redirect_with_error_alert("Unexpected behaviour occured during announcement creation", "/staff/admin/announcements/index.php");
exit;

