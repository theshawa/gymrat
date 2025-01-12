<?php

session_start();

require_once "../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    redirect_with_error_alert("Method not allowed", "./");
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login");

require_once "../../db/models/WorkoutSession.php";
require_once "../../db/models/Customer.php";

function start_workout()
{
    $workoutSession = new WorkoutSession();

    // end any existing workout
    $workoutSession->mark_all_live_as_ended_of_user($_SESSION['auth']['id']);
    $customer  = new Customer();
    $customer->fill([
        'id' => $_SESSION['auth']['id'],
    ]);
    try {
        $customer->get_by_id();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to start workout due to error(failed to fetch user info): " . $e->getMessage(), "./");
    }

    if (!$customer->workout) {
        redirect_with_error_alert("You don't have access to workout. Please contact admin.", "./");
    }

    $workoutSession->fill([
        'user' => $_SESSION['auth']['id'],
        'workout' => $customer->workout,
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

if ($_SESSION['workout_session']) {
    end_workout();
} else {
    start_workout();
}
