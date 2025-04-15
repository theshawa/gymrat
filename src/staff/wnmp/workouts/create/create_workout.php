<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}

if (!isset($_SESSION['workout'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/workouts");
}

$workout = unserialize($_SESSION['workout']);
if (!isset($_SESSION['exerciseTitles'])) {    
    $exerciseModel = new Exercise();
    $exerciseTitles = $exerciseModel->get_all_titles();
} else {
    $exerciseTitles = $_SESSION['exerciseTitles'];
}
$errors = [];


// Handle workout details update
if (isset($_POST['workout_name'], $_POST['workout_description'], $_POST['workout_duration'])) {
    $name = htmlspecialchars($_POST['workout_name']);
    $description = htmlspecialchars($_POST['workout_description']);
    $duration = htmlspecialchars($_POST['workout_duration']);

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($duration)) $errors[] = "Duration is required.";

    $workout->name = $name;
    $workout->description = $description;
    $workout->duration = $duration;
}

// Handle exercise updates
if (!empty($workout->exercises)) {
    foreach ($workout->exercises as $key => $exercise) {
        $edit_id = $exercise['edit_id'];

        $title_key = "exercise_title_$edit_id";
        $reps_key = "exercise_reps_$edit_id";
        $sets_key = "exercise_sets_$edit_id";
        $day_key = "exercise_day_$edit_id";

        if (isset($_POST[$title_key], $_POST[$reps_key], $_POST[$sets_key], $_POST[$day_key])) {
            $workout->exercises[$key]['title'] = htmlspecialchars($_POST[$title_key]);
            $workout->exercises[$key]['reps'] = htmlspecialchars($_POST[$reps_key]);
            $workout->exercises[$key]['sets'] = htmlspecialchars($_POST[$sets_key]);
            $workout->exercises[$key]['day'] = htmlspecialchars($_POST[$day_key]);
            $workout->exercises[$key]['isUpdated'] = true;
        }
    }
}

// Delete Logic
if (isset($_POST['delete_exercise'])) {
    $current_exercise_edit_id = htmlspecialchars($_POST['delete_exercise']);

    foreach ($workout->exercises as $key => $exercise) {
        if ($exercise['id'] == 0 && $exercise['edit_id'] == $current_exercise_edit_id) {
            // catch newly added exercises
            $workout->exercises[$key]['isDeleted'] = true;
        }
    }   
} 


// Add Logic
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $lastExercise = end($workout->exercises);
    $edit_id = $lastExercise ? $lastExercise["edit_id"] + 1 : 0;

    $newExercise = [
        "id" => 0,
        'workout_id' => 0,
        "edit_id" => $edit_id,
        "exercise_id" => 2147483647,
        "title" => $exerciseTitles[1],
        "sets" => 0,
        "reps" => 0,
        "day" => 0,
        "isUpdated" => true,
        "isDeleted" => false
    ];

    $workout->exercises[] = $newExercise;
}


// Validation
if (empty($workout->exercises) && (!isset($_POST['action']) || $_POST['action'] !== 'add')) {
    $errors[] = "At least one exercise is required. [0]";
}

if (!empty($workout->exercises)) {
    $allDeleted = true;
    $exerciseCombinationTracker = [];
    foreach ($workout->exercises as $exercise) {
        if (empty($exercise['isDeleted']) || !$exercise['isDeleted']) {
            $allDeleted = false;

            // Check for duplicate exercises
            $combinationKey = $exercise['title'] . '|' . $exercise['reps'] . '|' . $exercise['sets'] . '|' . $exercise['day'];
            if (isset($exerciseCombinationTracker[$combinationKey])) {
                $errors[] = "Duplicate exercise found: Title '{$exercise['title']}', Reps '{$exercise['reps']}', Sets '{$exercise['sets']}', Day '{$exercise['day']}'.";
            } else {
                $exerciseCombinationTracker[$combinationKey] = true;
            }
        }
    }
    if ($allDeleted) {
        $errors[] = "At least one exercise is required. [1]";
    }

    // Only get not deleted ones
    $activeExercises = array_filter($workout->exercises, function ($exercise) {
        return empty($exercise['isDeleted']) || !$exercise['isDeleted'];
    });

    $days = array_column($activeExercises, 'day');
    $max_day = !empty($days) ? max($days) : 0;

    if ($max_day < 1 || $max_day > 7) {
        $errors[] = "The number of days must be between 1 and 7.";
    }

    for ($day = 1; $day <= $max_day; $day++) {
        if (!in_array($day, $days)) {
            $errors[] = "There must be at least one exercise for each day between 1 and $max_day.";
            break;
        }
    }
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    $_SESSION['workout'] = serialize($workout);
    redirect_with_error_alert($error_message, "/staff/wnmp/workouts/create");
}

$_SESSION['workout'] = serialize($workout);

// Create Logic
if (isset($_POST['action']) && $_POST['action'] === 'create'){
    try {
        $workout->save();
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            redirect_with_error_alert("Failed to create workout due to an error: Workout with the same name already exists", "/staff/wnmp/workouts/create");
        }
        redirect_with_error_alert("Failed to create workout due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/create");
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
}

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/workouts/create");
?>

