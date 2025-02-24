<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}

if (!isset($_SESSION['workout'], $_SESSION['workout_id'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/workouts");
}

$workout = unserialize($_SESSION['workout']);
$current_workout_id = $_SESSION['workout_id'];
$lastExercise = end($workout->exercises);

$newExercise = [
    "id" => -1,
    'workout_id' => $current_workout_id,
    "exercise_id" => 2147483647,
    "title" => "Exercise",
    "sets" => 0,
    "reps" => 0,
    "isUpdated" => true,
    "isDeleted" => false
];

// Issues : what if the exercise id is already 2147483647
// Issues : how to make sure id is not replacing a already existing id
// make sure these new exercises are manually "INSERT" rather than "UPDATE"

$workout->exercises[] = $newExercise;

$_SESSION['workout'] = serialize($workout);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/edit?id=$current_workout_id");

