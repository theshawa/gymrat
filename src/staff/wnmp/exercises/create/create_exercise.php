<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Exercise.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/exercises");
    exit;
}

$name = $_POST['exercise_name'];
$description = $_POST['exercise_description'];
$video_link = $_POST['exercise_video_link'];
$muscle_group = $_POST['exercise_muscle_group'];
$difficulty_level = $_POST['exercise_difficulty_level'];
$type = $_POST['exercise_type'];
$equipment_needed = $_POST['exercise_equipment_needed'];

// Validation
$errors = [];

if (empty($name)) $errors[] = "Name is required.";
if (empty($description)) $errors[] = "Description is required.";
if (empty($muscle_group)) $errors[] = "Muscle group is required.";
if (empty($difficulty_level)) $errors[] = "Difficulty level is required.";
if (empty($type)) $errors[] = "Type is required.";


// image upload
$image = $_FILES['exercise_image']['name'] ? $_FILES['exercise_image'] : null;
if ($image) {
    try {
        $image = upload_file("staff-exercise-images", $image);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/wnmp/exercises/create");
        exit;
    }
}

$exercise = unserialize($_SESSION['exercise']);

// check for existing image and delete if found
if ($exercise->image && $image) {
    $old_image = $exercise->image;
    try {
        delete_file($old_image);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
    }
    $exercise->image = $image;
}

$exercise->name = $name;
$exercise->description = $description;
$exercise->video_link = $video_link;
$exercise->muscle_group = $muscle_group;
$exercise->difficulty_level = $difficulty_level;
$exercise->type = $type;
$exercise->equipment_needed = $equipment_needed;
$exercise->image = $image ?? $exercise->image;


$_SESSION['exercise'] = serialize($exercise);


if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/exercises/create");
    exit;
}


try {
    $exercise->save();
} catch (PDOException $e) {
    $_SESSION['exercise'] = $newExercise;
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['exercise']->name = "";
        redirect_with_error_alert("Failed to create exercise due to an error: Exercise with the same name already exists", "/staff/wnmp/exercises/create");
        exit;
    }
    redirect_with_error_alert("Failed to create exercise due to an error: " . $e->getMessage(), "/staff/wnmp/exercises/create");
    exit;
}

unset($_SESSION['exercise']);

redirect_with_success_alert("Exercise created successfully", "/staff/wnmp/exercises");
exit;