<?php

session_start();

require_once "../../../../db/models/Customer.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staffadmin/rats");
}

$customer = unserialize($_SESSION['customer']);
$errors = [];

$id = $customer->id;
// $fname = htmlspecialchars($_POST['customer_fname']);
// $lname = htmlspecialchars($_POST['customer_lname']);
$email = htmlspecialchars($_POST['customer_email']);
$phone = htmlspecialchars($_POST['customer_phone']);


// if (empty($fname)) $errors[] = "First name is required.";
// if (empty($lname)) $errors[] = "Last name is required.";
if (empty($email)) $errors[] = "Email is required.";
if (empty($phone)) $errors[] = "Phone number is required.";


$avatar = $_FILES['customer_avatar']['name'] ? $_FILES['customer_avatar'] : null;
if ($avatar) {
    try {
        $avatar = upload_file("customer-avatars", $avatar);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/admin/rats/profile/index.php?id=$id");
        exit;
    }
}

if ($customer->avatar && $avatar) {
    $old_avatar = $customer->avatar;
    try {
        delete_file($old_avatar);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
    }
}

// $customer->fname = $fname;
// $customer->lname = $lname;
$customer->email = $email;
$customer->phone = $phone;
$customer->avatar = $avatar ?? $customer->avatar;



if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/admin/rats/profile/index.php?id=$id");
    exit;
}

// Revert Logic
if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    $customer = new Customer();
    $customer->id = $id;
    $customer->get_by_id();
}

$_SESSION['customer'] = serialize($customer);


// Save Logic
if (isset($_POST['action']) && $_POST['action'] === 'edit'){
    try {
        $customer->save();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to update customer due to an error: " . $e->getMessage(), "/staff/admin/rats/profile/index.php?id=$id");
        exit;
    }
    
    unset($_SESSION['customer']);
    
    redirect_with_success_alert("Customer updated successfully", "/staff/admin/rats/view/index.php?id=$id");
    exit;
}

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/admin/rats/profile/index.php?id=$id");
exit;

