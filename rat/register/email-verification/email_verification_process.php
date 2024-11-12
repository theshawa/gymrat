<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("method not allowed");
}

if (!isset($_SESSION['customer_registration'])) {
    die("invalid request");
}

require_once "../../../db/models/CustomerEmailVerificationRequest.php";

$request = new CustomerEmailVerificationRequest();
$request->get_by_email($_SESSION['customer_registration']['email']);
if (!isset($request->code)) {
    die("invalid request");
}

if ($_POST['code'] !== $request->code) {
    $_SESSION['alert'] = "Invalid code!";
    header("Location: ../email-verification");
    exit();
}

$request->delete();

// create customer

require_once "../../../db/models/Customer.php";

$customer = new Customer();
$customer->fill([
    'fname' => $_SESSION['customer_registration']['fname'],
    'lname' => $_SESSION['customer_registration']['lname'],
    'email' => $_SESSION['customer_registration']['email'],
    'phone' => $_SESSION['customer_registration']['phone'],
    'password' => $_SESSION['customer_registration']['password'],
    'avatar' => $_SESSION['customer_registration']['avatar'],
]);
$customer->save();

$_SESSION['alert'] = "Registration successful! Please login.";
unset($_SESSION['customer_registration']);
header("Location: ../../login");
