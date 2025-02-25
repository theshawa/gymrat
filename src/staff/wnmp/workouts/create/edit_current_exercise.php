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
$current_exercise_edit_id = htmlspecialchars($_POST['exercise_edit_id']);

// Since we are adding workouts all exercise ids will be 0. so when one is updayed all are updated since they have same id

foreach ($workout->exercises as $key => $exercise) {
    if ($exercise['edit_id'] == $current_exercise_edit_id) {
        $workout->exercises[$key]['title'] = htmlspecialchars($_POST['exercise_title']);
        $workout->exercises[$key]['reps'] = htmlspecialchars($_POST['exercise_reps']);
        $workout->exercises[$key]['sets'] = htmlspecialchars($_POST['exercise_sets']);
        $workout->exercises[$key]['isUpdated'] = true;
    }
}


$_SESSION['workout'] = serialize($workout);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/create");