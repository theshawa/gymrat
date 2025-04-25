<?php


session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

require_once "../../auth-guards.php";
auth_not_required_guard("rat", "/rat");

$fname = htmlspecialchars($_POST['fname']);
$lname = htmlspecialchars($_POST['lname']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$phone = htmlspecialchars($_POST['phone']);

// check if email is already registered
require_once "../../db/models/Customer.php";
$already_customer = new Customer();
$already_customer->fill([
    'email' => $email,
]);

try {
    $already_customer->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "/rat/register");
    exit;
}

if ($already_customer->id) {
    redirect_with_error_alert("Email is already registered", "/rat/register");
    exit;
}

require_once "../../uploads.php";
$avatar = $_FILES['avatar']['name'] ? $_FILES['avatar'] : null;
if ($avatar) {
    // upload to temp folder
    try {
        $avatar = upload_file("tmp/customer-avatars", $avatar);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload avatar due to an error: " . $th->getMessage(), "/rat/register");
        exit;
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
$request->fill([
    'email' => $email,
]);

// check if a request already exists
try {
    $request->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to register customer due to error: " . $e->getMessage(), "/rat/register");
    exit;
}

if ($request->code) {
    redirect_with_error_alert("Email verification request already sent. Please check your email", "/rat/register/email-verification");
    exit;
}

$request->fill([
    'email' => $email,
    'code' => strval(rand(100000, 999999)),
]);

try {
    $request->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create email verification request due to error: " . $e->getMessage(), "/rat/register");
    exit;
}

require_once "../../send_email.php";

use PHPMailer\PHPMailer\Exception;

try {
    send_email(
        [
            'email' => $request->email,
            'name' => $request->email
        ],
        'Verify Your Email',
        'Your verification code is <b>' . $request->code . '</b>.'
    );
} catch (Exception $e) {
    redirect_with_error_alert("Failed to send email due to error: " . $e->getMessage(), "/rat/register");
    exit;
}

redirect_with_success_alert("Email verification request sent. Please check your email", "/rat/register/email-verification");
