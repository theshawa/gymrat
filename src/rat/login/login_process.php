<?php


require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/login");
}

require_once "../../auth-guards.php";
auth_not_required_guard("/rat");

require_once "../../db/models/Customer.php";

$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);

$user = new Customer();
$user->fill([
    "email" => $email,
]);

try {
    $user->get_by_email();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to login user due to error: " . $e->getMessage(), "./");
}

if (!$user->id) {
    redirect_with_error_alert("Invalid email or password", "/rat/login");
}

if (!password_verify($password, $user->password)) {
    redirect_with_error_alert("Invalid email or password", "/rat/login");
}

$_SESSION["auth"] = [
    'id' => $user->id,
    'email' => $user->email,
    'fname' => $user->fname,
    'lname' => $user->lname,
    'session_started_at' => time(),
    'activated' => false,
    'role' => 'rat'
];

if (!$user->membership_plan) {
    header("Location: ./no-subscription");
    exit;
}

require_once "../../db/models/MembershipPlan.php";

$membership_plan = new MembershipPlan();
try {
    $membership_plan->fill([
        "id" => $user->membership_plan
    ]);
    $membership_plan->get_by_id();
} catch (\Throwable $th) {
    redirect_with_error_alert("Failed to get membership plan due to error: " . $th->getMessage(), "./");
}

if (!$membership_plan->duration) {
    header("Location: ./no-subscription");
    exit;
}

$plan_expiry_date = $user->membership_plan_activated_at->add(new DateInterval("P" . $membership_plan->duration . "D"));
$now = new DateTime();

if ($plan_expiry_date < $now) {
    $user->membership_plan = 0;
    $user->membership_plan_activated_at = null;

    try {
        $user->update();
    } catch (PDOException $th) {
        redirect_with_error_alert("Failed to update user due to error: " . $th->getMessage(), "./");
    }

    header("Location: ./no-subscription");
    exit;
}

if (!$user->onboarded) {
    header("Location: /rat/onboarding/facts");
    exit;
}

$_SESSION["auth"]["activated"] = true;

redirect_with_success_alert("Logged in successfully", "/rat");
