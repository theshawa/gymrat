<?php

session_start();

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/WorkoutRequest.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}


$id = htmlspecialchars($_POST['id']);
$confirmation_workout = htmlspecialchars($_POST['confirmation_workout']);

if (empty($confirmation_workout)) {
    redirect_with_error_alert("No Workout is chosen for confirmation", "/staff/wnmp/workouts/requests/view?id=" . $id);
}

$workoutRequest = new WorkoutRequest();
try {
    $workoutRequest->get_by_id($id);
    $workoutRequest->confirm_request();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to confirm workout request due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/requests?filter=1");
}

require_once "../../../../../notifications/functions.php";
try {
    notify_trainer($workoutRequest->trainerId, "Workout Request Aknowledged", "The Workout you have requested has been created : " . $confirmation_workout, "wnmp manager" );
} catch (\Throwable $th) {
    redirect_with_info_alert("Confirmation successful, but failed to send notification: " . $th->getMessage(), "/staff/wnmp/workouts/requests?filter=1");
}


redirect_with_success_alert("Workout request confirmed successfully", "/staff/wnmp/workouts/requests?filter=1");
?>
