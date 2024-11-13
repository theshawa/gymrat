<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('method not allowed');
}

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$phone = $_POST['phone'];

if ($password !== $cpassword) {
    $_SESSION['alert'] = "Passwords do not match";
    header("Location: /rat/register");
    exit();
}

// check if email is already registered
require_once "../../db/models/Customer.php";
$already_customer = new Customer();
$already_customer->get_by_email($email);
if (isset($already_customer->id)) {
    $_SESSION['alert'] = "Email is already registered";
    header("Location: /rat/register");
    exit();
}

require_once "../../uploads.php";
$avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : null;
if ($avatar) {
    // upload to temp folder
    $avatar = upload_file("tmp/customer-avatars", $avatar);
    if (!$avatar) {
        die("failed upload avatar to temp.");
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
$request->get_by_email($email);
if (isset($request->code)) {
    $_SESSION['alert'] = "Email verification request already sent. Please check your email";
    header("Location: email-verification");
    exit();
}

$request->fill([
    'email' => $email,
    'code' => strval(rand(100000, 999999)),
]);
$request->create();

$_SESSION['alert'] = "Email verification request sent. Please check your email";
header("Location: email-verification");
