<?php

session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

// update user onboarded status
require_once "../../db/models/Customer.php";

if (!isset($_SESSION['subscribing'])) {
    die("You have to login first.");
}

$user = new Customer();
$user->fill([
    'id' => $_SESSION['subscribing'],
]);

$plan_id = htmlspecialchars($_POST['plan']);

try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch user due to error: " . $e->getMessage(), "./");
    exit;
}

require_once "../../db/models/MembershipPlan.php";
$plan = new MembershipPlan();
$plan->fill([
    'id' => $plan_id,
]);

try {
    $plan->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch plan due to error: " . $e->getMessage(), "./");
    exit;
}

require_once "../../db/models/MembershipPayment.php";
$payment = new MembershipPayment();
$payment->fill([
    'customer' => $user->id,
    'membership_plan' => $plan->id,
    'amount' => $plan->price,
]);

try {
    $payment->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create payment due to error: " . $e->getMessage(), "./");
    exit;
}

require_once "../../payhere/functions.php";

$fields = get_checkout_fields(
    $payment->amount,
    $payment->id,
    $plan->name,
    $user->fname,
    $user->lname,
    $user->email,
    $user->phone,
);

$action_url = payhere_config['action_url'];
echo "<form method='post' action='$action_url'>";
echo $fields;
echo "<p>Redirecting...</p>";
echo "</form>";

echo "<script type='text/javascript'>document.forms[0].submit();</script>";
