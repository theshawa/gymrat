<?php
// trainer/profile/profile_process.php

require_once __DIR__ . "/../../db/models/Trainer.php";

session_start();

if (!isset($_SESSION['auth'])) {
    header('Location: ../login/index.php');
    exit();
}

try {
    $trainer = new Trainer();
    $trainer->id = $_SESSION['auth']['id'];


    switch ($_POST['action']) {
        case 'update_profile':
            // Handle avatar upload if a new file was provided
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../uploads/trainers/';

                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Generate unique filename
                $fileExtension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $fileName = 'trainer_' . time() . '_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                // Move uploaded file
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
                    // Delete old avatar if exists
                    if (!empty($trainer->avatar) && file_exists($uploadDir . $trainer->avatar)) {
                        unlink($uploadDir . $trainer->avatar);
                    }
                    $trainer->avatar = $fileName;
                }
            }

            $fname = htmlspecialchars($_POST['fname']);
            $lname = htmlspecialchars($_POST['lname']);
            $bio = htmlspecialchars($_POST['bio']);
            $phone = htmlspecialchars($_POST['phone']);

            // Update trainer data
            $trainer->fname = $fname;
            $trainer->lname = $lname;
            // $trainer->username = $_POST['username'];
            $trainer->bio = $bio;
            $trainer->phone = $phone;

            // Save changes to database
            $trainer->save();

            // Update session data to reflect changes
            $_SESSION['auth']['fname'] = $fname;
            $_SESSION['auth']['lname'] = $lname;
            $_SESSION['auth']['bio'] = $bio;
            $_SESSION['auth']['phone'] = $phone;

            $_SESSION['message'] = "Profile updated successfully";
            break;

        case 'remove_avatar':
            // Handle avatar removal
            if (!empty($trainer->avatar)) {
                $avatarPath = __DIR__ . '/../../uploads/trainers/' . $trainer->avatar;
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }
                $trainer->avatar = null;
                $trainer->save();
                $_SESSION['auth']['avatar'] = null;
            }
            $_SESSION['message'] = "Profile picture removed";
            break;
    }

    header('Location: index.php');
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: index.php');
    exit();
}
