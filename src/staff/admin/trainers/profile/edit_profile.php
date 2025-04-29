<?php

session_start();

require_once "../../../../db/models/Trainer.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/trainers");
}

$trainer = unserialize($_SESSION['trainer']);
$do_password_update = 0;
$errors = [];

$id = $trainer->id;
// $fname = htmlspecialchars($_POST['trainer_fname']);
// $lname = htmlspecialchars($_POST['trainer_lname']);
// $username = htmlspecialchars($_POST['trainer_username']);
$password = $_POST['trainer_password'];
$confirm_password = $_POST['trainer_confirm_password'];
$phone = htmlspecialchars($_POST['trainer_phone']);
$bio = htmlspecialchars($_POST['trainer_bio']);

// if (empty($fname)) $errors[] = "First name is required.";
// if (empty($lname)) $errors[] = "Last name is required.";
// if (empty($username)) $errors[] = "Username is required.";
// if (empty($phone)) $errors[] = "Phone number is required.";
if (!empty($password) && empty($confirm_password)) $errors[] = "Password confirmation is required.";
if (!empty($password) && !empty($confirm_password)) {
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";
    $do_password_update = 1;
}


$avatar = $_FILES['trainer_avatar']['name'] ? $_FILES['trainer_avatar'] : null;
if ($avatar) {
    try {
        $avatar = upload_file("trainer-avatars", $avatar);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/admin/trainers/profile/index.php?id=$id");
        exit;
    }
}

if ($trainer->avatar && $avatar) {
    $old_avatar = $trainer->avatar;
    try {
        delete_file($old_avatar);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
    }
}

// $trainer->fname = $fname;
// $trainer->lname = $lname;
// $trainer->username = $username;
$trainer->phone = $phone ?? $trainer->phone;
$trainer->bio = $bio ?? $trainer->bio;
$trainer->avatar = $avatar ?? $trainer->avatar;
$trainer->password = $password ?? $trainer->password;

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/trainers/profile/index.php?id=$id");
    exit;
}

// Revert Logic
if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    $trainer = new Trainer();
    $trainer->id = $id;
    $trainer->get_by_id();
}

$_SESSION['trainer'] = serialize($trainer);

// Save Logic
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    try {
        $trainer->save();
        if ($do_password_update) $trainer->update_password();
    } catch (PDOException $e) {
        if ($e->getCode() === '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
            redirect_with_error_alert("Failed to update trainer: The username is already taken.", "/staff/admin/trainers/profile/index.php?id=$id");
        } else {
            redirect_with_error_alert("Failed to update trainer due to an error: " . $e->getMessage(), "/staff/admin/trainers/profile/index.php?id=$id");
        }
        exit;
    }

    unset($_SESSION['trainer']);

    redirect_with_success_alert("Trainer updated successfully", "/staff/admin/trainers/view/index.php?id=$id");
    exit;
}

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/admin/trainers/profile/index.php?id=$id");
exit;
