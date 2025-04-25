<?php
// trainer/profile/profile_process.php

require_once __DIR__ . "/../../db/models/Trainer.php";
require_once __DIR__ . "/../../alerts/functions.php";
require_once __DIR__ . "/../../uploads.php"; // Include the uploads helper

session_start();

if (!isset($_SESSION['auth'])) {
    header('Location: ../login/index.php');
    exit();
}

try {
    $trainer = new Trainer();
    $trainer->id = $_SESSION['auth']['id'];
    $trainer->get_by_id();

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_profile':
            // Get other form fields
            $fname = htmlspecialchars($_POST['fname']);
            $lname = htmlspecialchars($_POST['lname']);
            $bio = htmlspecialchars($_POST['bio']);
            $phone = htmlspecialchars($_POST['phone']);
            $updated_avatar = $_POST['updated_avatar'] ?? '';

            // Handle avatar update
            if ($updated_avatar === '' && $trainer->avatar) {
                // Avatar was cleared - delete the old file
                if (!delete_file($trainer->avatar)) {
                    error_log("Failed to delete old avatar: " . $trainer->avatar);
                }
                $trainer->avatar = null;
                error_log("Avatar removed");
            }
            // Check if there's a new file uploaded
            elseif (isset($_FILES['avatar']) && $_FILES['avatar']['name'] && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                // Delete old avatar if exists
                if ($trainer->avatar) {
                    if (!delete_file($trainer->avatar)) {
                        error_log("Failed to delete old avatar: " . $trainer->avatar);
                    }
                }

                // Upload the new avatar
                try {
                    $avatar = upload_file("trainer-avatars", $_FILES['avatar']);
                    $trainer->avatar = $avatar; // Store the full path relative to uploads
                    error_log("New avatar uploaded: " . $avatar);
                } catch (Exception $e) {
                    redirect_with_error_alert("Failed to upload avatar: " . $e->getMessage(), "edit.php");
                    exit;
                }
            }

            // Update trainer data
            $trainer->fname = $fname;
            $trainer->lname = $lname;
            $trainer->bio = $bio;
            $trainer->phone = $phone;

            // Debug before save
            error_log("Before save, trainer avatar: " . $trainer->avatar);

            // Save changes to database
            $trainer->save();

            // Debug after save
            error_log("After save, trainer avatar: " . $trainer->avatar);

            // Update session data to reflect changes
            $_SESSION['auth']['fname'] = $fname;
            $_SESSION['auth']['lname'] = $lname;
            $_SESSION['auth']['bio'] = $bio;
            $_SESSION['auth']['phone'] = $phone;
            $_SESSION['auth']['avatar'] = $trainer->avatar; // Update avatar in session
            
            // Add a cache-busting parameter to prevent browser caching
            $cache_buster = md5(time());
            $_SESSION['cache_buster'] = $cache_buster;

            redirect_with_success_alert("Profile updated successfully", "index.php");
            break;

        case 'remove_avatar':
            // Handle avatar removal
            if ($trainer->avatar) {
                if (!delete_file($trainer->avatar)) {
                    redirect_with_error_alert("Failed to delete avatar file", "edit.php");
                    exit;
                }
                $trainer->avatar = null;
                $trainer->save();
                $_SESSION['auth']['avatar'] = null;
            }
            redirect_with_success_alert("Profile picture removed", "index.php");
            break;

        default:
            redirect_with_error_alert("Invalid action", "edit.php");
            break;
    }
} catch (Exception $e) {
    redirect_with_error_alert("Error: " . $e->getMessage(), "edit.php");
    exit;
}