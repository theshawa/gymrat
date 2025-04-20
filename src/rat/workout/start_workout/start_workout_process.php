<?php

session_start();

require_once "../../../db/models/Customer.php";

$user = new Customer();
$user->fill([
    'id' => $_SESSION['auth']['id'],
]);
try {
    $user->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}

if (!$user->workout) {
    die("You don't have a workout plan. Please contact your trainer to get one.");
}

require_once "../../../db/models/Workout.php";

$workout = new Workout();
try {
    $workout->get_by_id($user->workout);
} catch (\Throwable $th) {
    die("Failed to get workout: " . $th->getMessage());
}

$days = [];

foreach ($workout->exercises as $exercise) {
    $d = $exercise['day'];
    if (in_array($d, $days)) continue;
    $days[] = $d;
}

sort($days);

require_once "../../../db/models/WorkoutSession.php";

$workout_session = new WorkoutSession();
$workout_session->fill([
    'user' => $user->id,
    'workout' => $workout->id,
]);
try {
    $workout_session->get_last_session();
} catch (\Throwable $th) {
    die("Failed to get workout session: " . $th->getMessage());
}

$day = null;
if ($workout_session->session_key) {
    if ($workout_session->ended_at) {
        // no active session
        $day = $workout_session->day + 1;
        if (!array_search($day, $days)) {
            $day = $days[0];
        }
    } else {
        // active session
        $day = $workout_session->day;
    }
} else {
    // no session yet
    $day = $days[0];
}

require_once "../../../db/models/WorkoutSessionKey.php";

$key = htmlspecialchars($_POST['key']);

$session_key = new WorkoutSessionKey();
$session_key->fill([
    'session_key' => $key,
]);

require_once "../../../alerts/functions.php";

require_once "../../../db/models/WorkoutSessionKey.php";

try {
    $session_key = new WorkoutSessionKey();
    $session_key->fill([
        'session_key' => $key,
    ]);
    $session_key->get_by_key();
    // delete existing session keys
    $session_key->delete_all();
    // create new session key
    $session_key->create($key);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to scan session key due to error: " . $e->getMessage(), "./");
    exit;
}

$workout_session = new WorkoutSession();
$workout_session->fill([
    'session_key' => $key,
    'user' => $_SESSION['auth']['id'],
    'workout' => 1,
    'day' => $day,
]);

try {
    $workout_session->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to start workout due to error: " . $e->getMessage(), "./");
    exit;
}

$_SESSION['workout_session'] = $workout_session->session_key;

redirect_with_success_alert("Workout started successfully.", "../");
