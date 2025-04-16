<?php

session_start();

require_once "../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

// save initial data
$gender = htmlspecialchars($_POST['gender']);
$age = (int) htmlspecialchars($_POST['age']);
$height = (float) htmlspecialchars($_POST['height']);
$weight = (float) htmlspecialchars($_POST['weight']);
$goal = htmlspecialchars($_POST['goal']);
$other_goal = htmlspecialchars($_POST['other_goal']);
$physical_activity_level = htmlspecialchars($_POST['physical_activity_level']);
$dietary_preference = htmlspecialchars($_POST['dietary_preference']);
$allergies = htmlspecialchars($_POST['allergies']);

require_once "../../db/models/CustomerInitialData.php";
$initial_data = new CustomerInitialData();
$initial_data->fill(
    [
        'customer_id' => $_SESSION['auth']['id'],
        'gender' => $gender,
        'age' => $age,
        'height' => $height,
        'weight' => $weight,
        'goal' => $goal,
        'other_goal' => $other_goal,
        'physical_activity_level' => $physical_activity_level,
        'dietary_preference' => $dietary_preference,
        'allergies' => $allergies,
    ]
);

try {
    $initial_data->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to create initial data due to error: " . $e->getMessage(), "./");
    exit;
}

// update user onboarded status

require_once "../../db/models/Customer.php";
$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id'],
]);
try {
    $user->get_by_id();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to fetch user due to error: " . $e->getMessage(), "./");
    exit;
}

$user->onboarded = true;

try {
    $user->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update user due to error: " . $e->getMessage(), "./");
    exit;
}

redirect_with_success_alert("Welcome to GYMRAT!", "/rat");
