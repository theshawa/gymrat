<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', '../');
}

require_once "../../../auth-guards.php";
auth_required_guard("/rat/login");

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
}


require_once "../../../uploads.php";
if ($updated_avatar !== $user->avatar) {
    // delete current avatar
    if ($user->avatar && !delete_file($user->avatar)) {
        redirect_with_error_alert("Failed to update avatar due to an error: failed to delete current avatar", "./");
    }

    $avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : null;
    if ($avatar) {
        // upload new avatar
        $avatar = upload_file("customer-avatars", $avatar);
        if (!$avatar) {
            redirect_with_error_alert("Failed to update avatar due to an error: failed to upload file", "./");
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
}

redirect_with_success_alert("Profile updated successfully", "../");
