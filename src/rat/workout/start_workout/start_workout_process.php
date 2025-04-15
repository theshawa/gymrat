<?php

session_start();

require_once "../../../db/models/Workout.php";
require_once "../../../db/models/WorkoutSessionKey.php";
require_once "../../../db/models/WorkoutSession.php";

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
    // generate qr code png
    $filePath = "../../../wsk_qr/QR.txt";
    // clear current content and add new key
    file_put_contents($filePath, $session_key->session_key);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to scan session key due to error: " . $e->getMessage(), "./");
}

$workout_session = new WorkoutSession();
$workout_session->fill([
    'session_key' => $key,
    'user' => $_SESSION['auth']['id'],
    'workout' => 1,
]);

try {
    $workout_session->create();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to start workout due to error: " . $e->getMessage(), "./");
}

$_SESSION['workout_session'] = $workout_session->session_key;

redirect_with_success_alert("Workout started successfully.", "../");
