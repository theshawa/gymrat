<?php

session_start();

$id = &$_SESSION['id'];

require_once "../../../../db/models/Exercise.php";

$originalExercise = new Exercise();

try {
    $originalExercise->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch exercise: " . $e->getMessage(), "/staff/wnmp");
    exit;
}

$_SESSION['exercise'] = serialize($originalExercise);

redirect_with_success_alert("Exercise reverted successfully", "/staff/wnmp/exercises/edit?id=" . $id);
exit;