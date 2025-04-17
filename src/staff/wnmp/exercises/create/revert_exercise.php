<?php

session_start();

require_once "../../../../db/models/Exercise.php";

$NewExercise = new Exercise();

try {
    $newExercise->fill([]);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch exercise: " . $e->getMessage(), "/staff/wnmp");
    exit;
}

$_SESSION['exercise'] = serialize($newExercise);

redirect_with_success_alert("Exercise reverted successfully", "/staff/wnmp/exercises/create");
exit;