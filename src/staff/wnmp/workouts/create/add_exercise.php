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

$workout = unserialize($_SESSION['workout']);
$lastExercise = end($workout->exercises);
$edit_id = $lastExercise ? $lastExercise["edit_id"] + 1 : 0;

$newExercise = [
    "id" => 0,
    'workout_id' => 0,
    "edit_id" => $edit_id,
    "exercise_id" => 2147483647,
    "title" => "Exercise",
    "sets" => 0,
    "reps" => 0,
    "isUpdated" => true,
    "isDeleted" => false
];

$workout->exercises[] = $newExercise;

$_SESSION['workout'] = serialize($workout);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/create");

