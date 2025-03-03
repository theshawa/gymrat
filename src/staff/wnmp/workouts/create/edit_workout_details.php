<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}

if (!isset($_SESSION['workout'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/workouts");
}

$name = htmlspecialchars($_POST['workout_name']);
$description = htmlspecialchars($_POST['workout_description']);
$duration = htmlspecialchars($_POST['workout_duration']);

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
    redirect_with_error_alert($error_message, "/staff/wnmp/workouts/create");
}

$workout = unserialize($_SESSION['workout']);
$workout->name = $name;
$workout->description = $description;
$workout->duration = $duration;
$_SESSION['workout'] = serialize($workout);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/create");