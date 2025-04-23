<?php
// File: src/trainer/workouts/request/request_process.php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
    exit;
}

$description = trim(htmlspecialchars($_POST['description']));
$workout_type = htmlspecialchars($_POST['workout_type']);
$duration = intval($_POST['duration']);

// Validate inputs
if (empty($description)) {
    redirect_with_error_alert("Please provide a description for the workout", "./");
    exit;
}

if (empty($workout_type)) {
    redirect_with_error_alert("Please select a workout type", "./");
    exit;
}

if ($duration < 1 || $duration > 365) {
    redirect_with_error_alert("Duration must be between 1 and 365 days", "./");
    exit;
}

// Format the description with additional information
$formatted_description =
    "Type: " . ucfirst($workout_type) . "\n" .
    "Duration: " . $duration . " days\n\n" .
    $description;

// Add to database
require_once "../../../../../db/models/WorkoutRequest.php";

$request = new WorkoutRequest();
$request->fill([
    'trainer_id' => $_SESSION['auth']['id'],
    'description' => $formatted_description
]);

try {
    $request->save();
    redirect_with_success_alert("Workout request submitted successfully. Our fitness team will create it soon.", "../../");
    exit;
} catch (Exception $e) {
    redirect_with_error_alert("Failed to submit request: " . $e->getMessage(), "./");
    exit;
}