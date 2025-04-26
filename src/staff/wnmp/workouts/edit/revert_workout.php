<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}

if ($_POST['workout_id'] !== $_SESSION['workout_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/workouts/edit?id=" . $_SESSION['workout_id']);
}

$id = htmlspecialchars($_POST['workout_id']);

// Unset the workout variable
unset($_SESSION['workout']);

redirect_with_success_alert("Workout changes reverted successfully", "/staff/wnmp/workouts/edit?id=" . $id);
?>
