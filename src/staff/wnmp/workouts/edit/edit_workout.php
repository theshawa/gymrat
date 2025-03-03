<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}
if ($_POST['workout_id'] !== $_SESSION['workout_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/workouts/edit?id=" . $_SESSION['workout_id']);
}

$id = htmlspecialchars($_POST['workout_id']);
$name = htmlspecialchars($_POST['workout_name']);
$description = htmlspecialchars($_POST['workout_description']);
$duration = htmlspecialchars($_POST['workout_duration']);

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}
if (empty($description)) {
    $errors[] = "Description is required.";
}
if (empty($duration)) {
    $errors[] = "Duration is required.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/workouts/edit?id=" . $id);
}

require_once "../../../../db/models/Workout.php";
require_once "../../../../db/models/Exercise.php";

$workout = unserialize($_SESSION['workout']);

if (!$workout) {
    redirect_with_error_alert("Failed to load workout from session", "/staff/wnmp/workouts");
}

$exerciseModel = new Exercise();
$exerciseTitles = $exerciseModel->get_all_titles();

foreach ($workout->exercises as &$exercise) {
    if (isset($exercise['title']) && $exercise['isUpdated'] && !$exercise['isDeleted']) {
        $exerciseId = array_search($exercise['title'], $exerciseTitles);
        if ($exerciseId === false) {
            redirect_with_error_alert("Exercise name not found: " . $exercise['title'], "/staff/wnmp/workouts/edit?id=" . $id);
        }
        $exercise['exercise_id'] = $exerciseId;
    }
}

$workout->id = $id;
$workout->name = $name;
$workout->description = $description;
$workout->duration = $duration;


try {
    $workout->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to edit workout due to an error: Workout with the same name already exists", "/staff/wnmp/workouts/edit?id=" . $id);
    }
    redirect_with_error_alert("Failed to update workout due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/edit?id=" . $id);
}

unset($_SESSION['workout']);
unset($_SESSION['workout_id']);

redirect_with_success_alert("Workout updated successfully", "/staff/wnmp/workouts/view?id=" . $id);
?>