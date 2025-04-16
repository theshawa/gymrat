<?php

session_start();

require_once "../../../alerts/functions.php";

if (!isset($_SESSION['subscribing'])) {
    die("You are not in the process of subscribing to a plan.");
    exit;
}

$order_id = htmlspecialchars($_GET['order_id']);


if (empty($order_id)) {
    redirect_with_error_alert("Order ID is required", "../");
    exit;
}

require_once "../../../db/models/MembershipPayment.php";

$payment = new MembershipPayment();
$payment->fill([
    'id' => $order_id,
]);

try {
    $payment->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Subscription failed! Failed to fetch payment due to error: " . $e->getMessage(), "../");
    exit;
}

// mark completed
try {
    $payment->mark_completed();
} catch (PDOException $e) {
    redirect_with_error_alert("Subscription failed! Failed to mark payment as completed due to error: " . $e->getMessage(), "../");
    exit;
}

// subscribe user to the plan
require_once "../../../db/models/Customer.php";
$user = new Customer();
$user->fill([
    'id' => $payment->customer,
]);

try {
    $user->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Subscription failed! Failed to fetch customer due to error: " . $e->getMessage(), "../");
    exit;
}

$user->membership_plan = $payment->membership_plan;
$user->membership_plan_activated_at = new DateTime();

try {
    $user->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Subscription failed! Failed to update user due to error: " . $e->getMessage(), "../");
    exit;
}

unset($_SESSION['subscribing']);

$_SESSION["auth"] = [
    'id' => $user->id,
    'email' => $user->email,
    'fname' => $user->fname,
    'lname' => $user->lname,
    'session_started_at' => time(),
    'role' => 'rat',
];

require_once "../../../db/models/MembershipPlan.php";
$plan = new MembershipPlan();
$plan->fill([
    'id' => $user->membership_plan,
]);

try {
    $plan->get_by_id();
} catch (Exception $e) {
    redirect_with_info_alert("Subscription successful! But failed to send email due to error: Failed to fetch plan due to error: " . $e->getMessage(), $user->onboarded ? "/rat" : "/rat/onboarding/facts");
    exit;
}

require_once "../../../phpmailer/send-mail.php";

try {
    send_mail(
        [
            'email' => $user->email,
            'name' => $user->fname . " " . $user->lname
        ],
        'Subscription Activated',
        'Your subscription to <b>' . $plan->name . '</b> plan has been activated successfully. <br> <br> <a href="https://localhost/rat/login">Click here</a> to login to your account.'
    );
} catch (\Throwable $e) {
    redirect_with_info_alert("Subscription successful! But failed to send email due to error: " . $e->getMessage(), $user->onboarded ? "/rat" : "/rat/onboarding/facts");
    exit;
}

redirect_with_success_alert("Subscription Plan activated successfully", $user->onboarded ? "/rat" : "/rat/onboarding/facts");
