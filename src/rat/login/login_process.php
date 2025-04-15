<?php

session_start();

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/login");
}

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

$_SESSION['subscribing'] = $user->id;

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

$plan_expiry_date = $user->membership_plan_activated_at;
$plan_expiry_date->add(new DateInterval("P" . $membership_plan->duration . "D"));
$now = new DateTime();

if ($plan_expiry_date < $now) {
    $user->membership_plan = null;
    $user->membership_plan_activated_at = null;

    try {
        $user->update();
    } catch (PDOException $th) {
        redirect_with_error_alert("Failed to update user due to error: " . $th->getMessage(), "./");
    }

    header("Location: ./no-subscription");
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

if (!$user->onboarded) {
    redirect_with_success_alert("Logged in successfully", "/rat/onboarding/facts");
    exit;
}

// end any existing workout sessions
require_once "../../db/models/WorkoutSession.php";
$workout_session_model = new WorkoutSession();
try {
    $workout_session_model->fill([
        'user' => $user->id,
    ]);
    $workout_sessions = $workout_session_model->get_all_by_user();
    foreach ($workout_sessions as $session) {
        if ($session->ended_at) {
            continue;
        }
        if ($session->get_duration_in_hours() > 4) {
            $session->mark_ended((clone $session->started_at)->modify('+4 hours'));
        } else {
            $session->mark_ended();
        }
    }
} catch (\Throwable $th) {
    unset($_SESSION['auth']);
    redirect_with_error_alert("Failed to reset workout sessions due to an error: " . $th->getMessage(), "./");
}

redirect_with_success_alert("Logged in successfully", "/rat");
