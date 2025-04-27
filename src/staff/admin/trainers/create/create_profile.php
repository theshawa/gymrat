<?php

session_start();

require_once "../../../../db/models/Trainer.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/admin/trainers");
}

$errors = [];

$fname = htmlspecialchars($_POST['trainer_fname']);
$lname = htmlspecialchars($_POST['trainer_lname']);
$username = htmlspecialchars($_POST['trainer_username']);
$phone = htmlspecialchars($_POST['trainer_phone']);
$password = $_POST['trainer_password'];
$confirm_password = $_POST['trainer_confirm_password'];
$bio = htmlspecialchars($_POST['trainer_bio']);

if (empty($fname)) $errors[] = "First name is required.";
if (empty($lname)) $errors[] = "Last name is required.";
if (empty($username)) $errors[] = "Username is required.";
if (empty($phone)) $errors[] = "Phone number is required.";
if (empty($password)) $errors[] = "Password is required.";
if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

$avatar = $_FILES['trainer_avatar']['name'] ? $_FILES['trainer_avatar'] : null;
if ($avatar) {
    try {
        $avatar = upload_file("trainer-avatars", $avatar);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/admin/trainers/create/index.php");
        exit;
    }
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/trainers/create/index.php");
    exit;
}

$trainer = new Trainer();
$trainer->fname = $fname;
$trainer->lname = $lname;
$trainer->username = $username;
$trainer->phone = $phone;
$trainer->password = $password; 
$trainer->bio = $bio;
$trainer->avatar = $avatar;

$_SESSION['trainer'] = serialize($trainer);

try {
    $trainer->save();
} catch (PDOException $e) {
    if ($e->getCode() === "23000" && strpos($e->getMessage(), "Duplicate entry") !== false) {
        redirect_with_error_alert("Username already exists. Please choose a different username.", "/staff/admin/trainers/create/index.php");
    } else {
        redirect_with_error_alert("Failed to create trainer due to an error: " . $e->getMessage(), "/staff/admin/trainers/create/index.php");
    }
    exit;
}

redirect_with_success_alert("Trainer created successfully", "/staff/admin/trainers/index.php");
exit;
