<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}

if (!isset($_SESSION['workout'], $_SESSION['workout_id'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/workouts");
}

$workout = &$_SESSION['workout'];
$workout_id =  &$_SESSION['workout_id'];
$current_workout_id = $_SESSION['workout']['id'];
$lastExercise = end($workout["exercise"]);

$newExercise = [
    "id" => $lastExercise['id'] + 1,
    "title" => "Exercise",
    "sets" => 0,
    "reps" => 0
];

$workout["exercise"][] = $newExercise;

$_SESSION['workout'] = $workout;


if ($workout_id == $current_workout_id) {
    redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/create?id=$current_workout_id");
}

redirect_with_error_alert("Action cannot be performed", "/staff/wnmp/workouts");
