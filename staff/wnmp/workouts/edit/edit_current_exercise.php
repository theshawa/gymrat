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

$current_exercise_id = htmlspecialchars($_POST['exercise_id']);


foreach ($workout['exercise'] as $key => $exercise) {
    if ($exercise['id'] == $current_exercise_id) {
        $workout['exercise'][$key]['title'] = htmlspecialchars($_POST['exercise_title']);
        $workout['exercise'][$key]['reps'] = htmlspecialchars($_POST['exercise_reps']);
        $workout['exercise'][$key]['sets'] = htmlspecialchars($_POST['exercise_sets']);
        $status = "success";
    }
}

$_SESSION['workout'] = $workout;

if ($workout_id == $current_workout_id) {
    redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/edit?id=$current_workout_id");
}

redirect_with_error_alert("Action cannot be performed", "/staff/wnmp/workouts");

