<?php

session_start();

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../db/models/WorkoutSession.php";

function start_workout()
{
    $workoutSession = new WorkoutSession();

    // end any existing workout
    $workoutSession->mark_all_live_as_ended_of_user($_SESSION['auth']['id']);

    $workoutSession->fill([
        'user' => $_SESSION['auth']['id'],
        'workout' => 1,
    ]);

    try {
        $workoutSession->create();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to start workout due to error: " . $e->getMessage(), "./");
    }

    $_SESSION['workout_session'] = $workoutSession->id;
    redirect_with_success_alert("Workout started successfully.", "./");
}

function end_workout()
{
    $workout_session_id = $_SESSION['workout_session'];

    try {
        $workoutSession = new WorkoutSession();
        $workoutSession->fill([
            'id' => $workout_session_id,
        ]);
        $workoutSession->mark_ended();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to end workout due to error: " . $e->getMessage(), "./");
    }

    unset($_SESSION['workout_session']);
    redirect_with_success_alert("Workout ended successfully.", "../");
}

if (isset($_SESSION['workout_session'])) {
    end_workout();
} else {
    start_workout();
}
