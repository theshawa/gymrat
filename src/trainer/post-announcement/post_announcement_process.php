<?php

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("trainer", "../login");

require_once "../../db/models/Announcement.php";

$title = htmlspecialchars($_POST['title']);
$message = trim(htmlspecialchars($_POST['message']));
$valid_till = trim(htmlspecialchars($_POST['valid_till']));

$announcement = new Announcement();

$announcement->fill([
    'title' => $title,
    'message' => $message,
    'valid_till' => $valid_till,
    'to_all' => 'rats',
    'source' => $_SESSION['auth']['id'],
]);

try {
    $announcement->create();
    // TODO: Send Email
} catch (PDOException $e) {
    redirect_with_error_alert("An error occurred: " . $e->getMessage(), "./");
}

require_once "../../notifications/functions.php";

redirect_with_success_alert("Announcement posted sucessfully. Your customers will be notified.", "./");
