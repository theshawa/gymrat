<?php

session_start();

$workout_session_id = $_SESSION['workout_session'];

require_once "../../db/models/WorkoutSession.php";
require_once "../../alerts/functions.php";

try {
    $workoutSession = new WorkoutSession();
    $workoutSession->fill([
        'session_key' => $workout_session_id,
    ]);
    $workoutSession->get_by_session_key();
    $workoutSession->mark_ended();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to end workout due to error: " . $e->getMessage(), "./");
    exit;
}

unset($_SESSION['workout_session']);
redirect_with_success_alert("Workout ended successfully.", "../");
