<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}

if (!isset($_SESSION['mealPlan'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/meal-plans");
}

$name = htmlspecialchars($_POST['mealplan_name']);
$description = htmlspecialchars($_POST['mealplan_description']);
$duration = htmlspecialchars($_POST['mealplan_duration']);

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}
if (empty($description)) {
    $errors[] = "Description is required.";
}
if (empty($duration)) {
    $errors[] = "Duration is required.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/meal-plans/create");
}

$mealPlan = unserialize($_SESSION['mealPlan']);
$mealPlan->name = $name;
$mealPlan->description = $description;
$mealPlan->duration = $duration;
$_SESSION['mealPlan'] = serialize($mealPlan);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/meal-plans/create");
?>
