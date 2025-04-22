<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

$fname = htmlspecialchars($_POST['fname']);
$lname = htmlspecialchars($_POST['lname']);
$phone = htmlspecialchars($_POST['phone']);
$updated_avatar = htmlspecialchars($_POST['updated_avatar']);

require_once "../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    "id" => $_SESSION['auth']['id']
]);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to get user due to error: " . $e->getMessage(), "./");
    exit;
}


require_once "../../../uploads.php";
if ($updated_avatar !== $user->avatar) {
    // delete current avatar
    if ($user->avatar && !delete_file($user->avatar)) {
        redirect_with_error_alert("Failed to update avatar due to an error: failed to delete current avatar", "./");
        exit;
    }

    $avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : null;
    if ($avatar) {
        // upload new avatar
        try {
            $avatar = upload_file("customer-avatars", $avatar);
        } catch (Exception $e) {
            redirect_with_error_alert("Failed to update avatar due to an error: " . $e->getMessage(), "./");
            exit;
        }
        $user->avatar = $avatar;
    } else {
        $user->avatar = null;
    }
}

$user->fname = $fname;
$user->lname = $lname;
$user->phone = $phone;

try {
    $user->update();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update user due to error: " . $e->getMessage(), "./");
    exit;
}

$_SESSION['auth'] = [
    ...$_SESSION['auth'],
    'fname' => $user->fname,
    'lname' => $user->lname,
    'phone' => $user->phone,
    'avatar' => $user->avatar
];

redirect_with_success_alert("Profile updated successfully", "../");
