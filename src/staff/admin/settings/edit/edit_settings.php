<?php

session_start();

require_once "../../../../db/models/Settings.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

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
$gym_name = htmlspecialchars($_POST['gym_name']);
$gym_desc = htmlspecialchars($_POST['gym_desc']);
$gym_address = htmlspecialchars($_POST['gym_address']);
$max_capacity = htmlspecialchars($_POST['max_capacity']);
$min_workout_time = htmlspecialchars($_POST['min_workout_time']);
$show_widgets = isset($_POST['show_widgets']) ? (int)$_POST['show_widgets'] : 1; 

if (empty($contact_email)) $errors[] = "contact_email is required.";
if (empty($contact_phone)) $errors[] = "contact_phone is required.";
if (empty($workout_session_expiry)) $errors[] = "workout_session_expiry is required.";
if (empty($max_capacity)) $errors[] = "max_capacity is required.";
if (empty($min_workout_time)) $errors[] = "min_workout_time is required.";


$banner = $_FILES['gym_banner']['name'] ? $_FILES['gym_banner'] : null;
if ($banner) {
    try {
        $banner = upload_file("default-images", $banner);    
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/admin/settings/edit");
        exit;
    }
}

if ($settings->gym_banner && $banner) {
    $old_banner = $settings->gym_banner;
    try {
        delete_file($old_banner);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
    }
}



$settings->contact_email = $contact_email;
$settings->contact_phone = $contact_phone;
$settings->workout_session_expiry = $workout_session_expiry;
$settings->gym_name = $gym_name;
$settings->gym_desc = $gym_desc;
$settings->gym_address = $gym_address;
$settings->max_capacity = $max_capacity;
$settings->min_workout_time = $min_workout_time;
$settings->show_widgets = $show_widgets; // Assign the validated integer value
$settings->gym_banner = $banner ?? $settings->gym_banner;


if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/settings/edit");
}

$_SESSION['settings'] = serialize($settings);

if (isset($_POST['action']) && $_POST['action'] === 'edit') {
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