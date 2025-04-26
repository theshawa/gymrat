<?php
// File: src/trainer/customers/profile/workout/request/request_process.php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
    exit;
}

$customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
$workout_name = trim(htmlspecialchars($_POST['workout_name'] ?? ''));
$description = trim(htmlspecialchars($_POST['description']));
$workout_type = htmlspecialchars($_POST['workout_type']);
$duration = intval($_POST['duration']);
$priority = htmlspecialchars($_POST['priority'] ?? 'normal');

// Validate inputs
if (!$customerId) {
    redirect_with_error_alert("Invalid customer ID", "./");
    exit;
}

if (empty($workout_name)) {
    redirect_with_error_alert("Please provide a name for the workout", "./?id=" . $customerId);
    exit;
}

if (empty($description)) {
    redirect_with_error_alert("Please provide a description for the workout", "./?id=" . $customerId);
    exit;
}

if (empty($workout_type)) {
    redirect_with_error_alert("Please select a workout type", "./?id=" . $customerId);
    exit;
}

if ($duration < 1 || $duration > 365) {
    redirect_with_error_alert("Duration must be between 1 and 365 days", "./?id=" . $customerId);
    exit;
}

// Process exercise recommendations if provided
$exercises = [];
if (isset($_POST['exercise_id']) && is_array($_POST['exercise_id'])) {
    $exerciseIds = $_POST['exercise_id'];
    $exerciseDays = $_POST['exercise_day'] ?? [];
    $exerciseSets = $_POST['exercise_sets'] ?? [];
    $exerciseReps = $_POST['exercise_reps'] ?? [];

    for ($i = 0; $i < count($exerciseIds); $i++) {
        if (!empty($exerciseIds[$i])) {
            $exercises[] = [
                'id' => intval($exerciseIds[$i]),
                'd' => isset($exerciseDays[$i]) ? intval($exerciseDays[$i]) : 1,
                's' => isset($exerciseSets[$i]) ? intval($exerciseSets[$i]) : 3,
                'r' => isset($exerciseReps[$i]) ? intval($exerciseReps[$i]) : 10
            ];
        }
    }
}

// Get exercise names for the detail description
$exerciseDetails = [];
if (!empty($exercises)) {
    try {
        require_once "../../../../../db/models/Exercise.php";
        $exerciseModel = new Exercise();
        $allExercises = $exerciseModel->get_all_titles();

        foreach ($exercises as $exercise) {
            if (isset($allExercises[$exercise['id']])) {
                $exerciseDetails[] = sprintf(
                    "%s: %d sets of %d reps (Day %d)",
                    $allExercises[$exercise['id']],
                    $exercise['s'],
                    $exercise['r'],
                    $exercise['d']
                );
            }
        }
    } catch (Exception $e) {
        error_log("Error fetching exercise names: " . $e->getMessage());
    }
}

// Format the description with additional information in a structured way
$formatted_description = [
    'name' => $workout_name,
    'type' => $workout_type,
    'duration' => $duration,
    'priority' => $priority,
    'description' => $description,
    'exercises' => $exercises,
    'customer_id' => $customerId,
    'trainer_id' => $_SESSION['auth']['id']
];

// Create a readable description for the database
$readable_description =
    "Name: " . $workout_name . "\n" .
    "Type: " . ucfirst($workout_type) . "\n" .
    "Duration: " . $duration . " days\n" .
    "Priority: " . ucfirst($priority) . "\n\n" .
    $description . "\n\n";

// Add a shortened version of the exercises to avoid exceeding database column size
if (!empty($exerciseDetails)) {
    $readable_description .= "Recommended Exercises: ";

    // Only include the first 3 exercises in the text description to save space
    $shortExerciseList = array_slice($exerciseDetails, 0, 3);
    $readable_description .= implode(", ", $shortExerciseList);

    if (count($exerciseDetails) > 3) {
        $readable_description .= " and " . (count($exerciseDetails) - 3) . " more exercises";
    }
}

// Combine the JSON and readable text
$combinedDescription = json_encode($formatted_description) . "\n\n" . $readable_description;

// Add to database
require_once "../../../../../db/models/WorkoutRequest.php";

try {
    $request = new WorkoutRequest();
    $request->fill([
        'trainer_id' => $_SESSION['auth']['id'],
        'description' => $combinedDescription
    ]);

    $request->save();

    // Notify the client that a request has been submitted
    require_once "../../../../../notifications/functions.php";

    if (function_exists('notify_rat')) {
        notify_rat(
            $customerId,
            "Workout Plan Request Submitted",
            "Your trainer has requested a custom workout plan for you. Our fitness team will create it soon."
        );
    }

    // Also send notification to the staff members responsible for workout plans
    try {
        // Get the staff ID for the workout and meal plan manager (wnmp role)
        $conn = Database::get_conn();
        $sql = "SELECT id FROM staff WHERE role = 'wnmp' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $staffId = $stmt->fetchColumn();

        if ($staffId) {
            // Send notification to the staff member
            $staffTitle = "New Workout Plan Request";
            $staffMessage = "Trainer " . $_SESSION['auth']['fname'] . " " . $_SESSION['auth']['lname'] .
                " has requested a custom workout plan for client #" . $customerId .
                ". Priority: " . ucfirst($priority);

            // This function may not exist in your system - adjust as needed
            if (function_exists('notify_staff')) {
                notify_staff($staffId, $staffTitle, $staffMessage);
            }
        }
    } catch (Exception $e) {
        // Log error but continue with the process
        error_log("Failed to notify staff: " . $e->getMessage());
    }

    redirect_with_success_alert("Workout request submitted successfully. Our fitness team will create it soon.", "../?id=" . $customerId);
    exit;
} catch (Exception $e) {
    redirect_with_error_alert("Failed to submit request: " . $e->getMessage(), "./?id=" . $customerId);
    exit;
}