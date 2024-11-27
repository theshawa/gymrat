<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/exercises");
}
if ($_POST['exercise_id'] !== $_SESSION['exercise_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/exercises/edit?id=" . $_SESSION['exercise_id']);
}

$id = $_POST['exercise_id'];
$name = $_POST['exercise_name'];
$description = $_POST['exercise_description'];
$video_link = $_POST['exercise_video_link'];
$muscle_group = $_POST['exercise_muscle_group'];
$difficulty_level = $_POST['exercise_difficulty_level'];
$type = $_POST['exercise_type'];
$equipment_needed = $_POST['exercise_equipment_needed'];

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}
if (empty($description)) {
    $errors[] = "Description is required.";
}
//if (empty($video_link)) {
//    $errors[] = "Video link is required.";
//}
if (empty($muscle_group)) {
    $errors[] = "Muscle group is required.";
}
if (empty($difficulty_level)) {
    $errors[] = "Difficulty level is required.";
}
if (empty($type)) {
    $errors[] = "Type is required.";
}
if (empty($equipment_needed)) {
    $errors[] = "Equipment needed is required.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/exercises/edit?id=" . $id);
}

require_once "../../../../db/models/Exercise.php";

$newExercise = new Exercise();
$newExercise->fill([
    'id' => $id,
    'name' => $name,
    'description' => $description,
    'video_link' => $video_link,
    'muscle_group' => $muscle_group,
    'difficulty_level' => $difficulty_level,
    'type' => $type,
    'equipment_needed' => $equipment_needed
]);

try {
    $newExercise->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to edit exercise due to an error: Exercise with the same name already exists", "/staff/wnmp/exercises/edit?id=" . $id);
    }
    redirect_with_error_alert("Failed to update exercise due to an error: " . $e->getMessage(), "/staff/wnmp/exercises/edit?id=" . $id);
}

unset($_SESSION['exercise']);
unset($_SESSION['exercise_id']);

redirect_with_success_alert("Exercise updated successfully", "/staff/wnmp/exercises/view?id=" . $id);



