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

foreach ($workout->exercises as $key => $exercise) {
    if ($exercise['id'] == 0 && $exercise['edit_id'] == $current_exercise_edit_id) {
        // catch newly added exercises
        $workout->exercises[$key]['isDeleted'] = true;
    }
}

$_SESSION['workout'] = serialize($workout);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/create");
