<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
    exit;
}

$workout = new Workout();
$workout->fill([]);
$_SESSION['workout'] = serialize($workout);


redirect_with_success_alert("Workout changes reverted successfully", "/staff/wnmp/workouts/create");
exit;
?>
