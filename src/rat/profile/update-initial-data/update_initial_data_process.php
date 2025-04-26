<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

$gender = htmlspecialchars($_POST['gender']);
$age = (int) htmlspecialchars($_POST['age']);
$height = (float) htmlspecialchars($_POST['height']);
$weight = (float) htmlspecialchars($_POST['weight']);
$goal = htmlspecialchars($_POST['goal']);
$other_goal = htmlspecialchars($_POST['other_goal']);
$physical_activity_level = htmlspecialchars($_POST['physical_activity_level']);
$dietary_preference = htmlspecialchars($_POST['dietary_preference']);
$allergies = htmlspecialchars($_POST['allergies']);

require_once "../../../db/models/CustomerInitialData.php";
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

require_once "../../../alerts/functions.php";

try {
    $initial_data->update();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update initial data due to error: " . $e->getMessage(), "./");
}

redirect_with_success_alert("Initial data updated successfully", "../");
