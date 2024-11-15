<?php

session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_error_alert('Method not allowed', '/rat/register');
}

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$phone = $_POST['phone'];

if ($password !== $cpassword) {
    redirect_with_error_alert("Passwords do not match", "/rat/register");
}

// check if email is already registered
require_once "../../db/models/Customer.php";
$already_customer = new Customer();

try {
    $already_customer->get_by_email($email);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "/rat/register");
}

if (isset($already_customer->id)) {
    redirect_with_error_alert("Email is already registered", "/rat/register");
}

require_once "../../uploads.php";
$avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : null;
if ($avatar) {
    // upload to temp folder
    $avatar = upload_file("tmp/customer-avatars", $avatar);
    if (!$avatar) {
        redirect_with_error_alert("Failed to upload avatar due to an error: failed to upload file", "/rat/register");
    }
}

$_SESSION['customer_registration'] = [
    'fname' => $fname,
    'lname' => $lname,
    'email' => $email,
    'phone' => $phone,
    'avatar' => $avatar,
    'password' => $password,
];

require_once "../../db/models/CustomerEmailVerificationRequest.php";

$request = new CustomerEmailVerificationRequest();

// check if a request already exists
try {
    $request->get_by_email($email);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "/rat/register");
}

if (isset($request->code)) {
    redirect_with_error_alert("Email verification request already sent. Please check your email", "/rat/register/email-verification");
}

$request->fill([
    'email' => $email,
    'code' => strval(rand(100000, 999999)),
]);

try {
    $request->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create email verification request due to error: " . $e->getMessage(), "/rat/register");
}


redirect_with_info_alert("Email verification request sent. Please check your email", "/rat/register/email-verification");
