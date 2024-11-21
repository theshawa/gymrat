<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/exercises");
}

$name = $_POST['exercise_name'];
$description = $_POST['exercise_description'];
$video_link = $_POST['exercise_video_link'];

require_once "../../../../db/models/Exercise.php";

$newExercise = new Exercise();
$newExercise->fill([
    'name' => $name,
    'description' => $description,
    'video_link' => $video_link
]);

try {
    $newExercise->save();
} catch (PDOException $e) {
    $_SESSION['exercise'] = $newExercise;
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['exercise']->name = "";
        redirect_with_error_alert("Failed to edit exercise due to an error: Exercise with the same name already exists", "/staff/wnmp/exercises/create");
    }
    redirect_with_error_alert("Failed to update exercise due to an error: " . $e->getMessage(), "/staff/wnmp/exercises/create");
}

session_unset();
session_destroy();

redirect_with_success_alert("Exercise created successfully", "/staff/wnmp/exercises");