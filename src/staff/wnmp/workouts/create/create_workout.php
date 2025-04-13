<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}

$workout = unserialize($_SESSION['workout']);

try {
    $workout->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to create workout due to an error: Workout with the same name already exists", "/staff/wnmp/workouts/create");
    }
    redirect_with_error_alert("Failed to create workout due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/create");
}

if (!isset($_SESSION['exerciseTitles'])){    
    $exerciseModel = new Exercise();
    $exerciseTitles = $exerciseModel->get_all_titles();
} else {
    $exerciseTitles = $_SESSION['exerciseTitles'];
}

foreach ($workout->exercises as &$exercise) {
    if (isset($exercise['title'])) {
        $exerciseId = array_search($exercise['title'], $exerciseTitles);
        if ($exerciseId === false) {
            redirect_with_error_alert("Exercise name not found: " . $exercise['title'], "/staff/wnmp/workouts/create");
        }
        $exercise['exercise_id'] = $exerciseId;
        $exercise['workout_id'] = $workout->id;
    }
}

try {
    $workout->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update exercises due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/create");
}

redirect_with_success_alert("Workout created successfully", "/staff/wnmp/workouts/view?id=" . $workout->id);
?>
