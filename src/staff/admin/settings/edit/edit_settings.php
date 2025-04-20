<?php

session_start();

require_once "../../../../db/models/Settings.php";
require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/settings/edit");
    exit;
}

$settings = unserialize($_SESSION['settings']);

// Revert Logic
if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    $settings = new Settings();
    $settings->get_all();
}

$contact_email = htmlspecialchars($_POST['contact_email']);
$contact_phone = htmlspecialchars($_POST['contact_phone']);
$workout_session_expiry = htmlspecialchars($_POST['workout_session_expiry']);

if (empty($contact_email)) $errors[] = "contact_email is required.";
if (empty($contact_phone)) $errors[] = "contact_phone is required.";
if (empty($workout_session_expiry)) $errors[] = "workout_session_expiry is required.";

$settings->contact_email = $contact_email;
$settings->contact_phone = $contact_phone;
$settings->workout_session_expiry = $workout_session_expiry;

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/settings/edit");
}


$_SESSION['settings'] = serialize($settings);

if (isset($_POST['action']) && $_POST['action'] === 'edit'){
    try {
        $settings->save();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to update settings: " . $e->getMessage(), "/staff/admin/settings/edit");
    }

    unset($_SESSION['settings']);
    redirect_with_success_alert("Settings updated successfully", "/staff/admin/settings/index.php");
    exit;
}

redirect_with_success_alert("Action successful. Press save changes to save", "/staff/admin/settings/edit");
exit;