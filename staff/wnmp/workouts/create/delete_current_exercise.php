<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

if (!isset($_SESSION['workout'], $_SESSION['workout_id'])) {
    die("Session data not set.");
}

var_dump($_POST);

$workout = &$_SESSION['workout'];
$workout_id =  &$_SESSION['workout_id'];
$current_workout_id = $_SESSION['workout']['id'];
$current_exercise_id = $_POST['exercise_id'];
$status = "failed";

foreach ($workout['exercise'] as $key => $exercise) {
    if ($exercise['id'] == $current_exercise_id) {
        unset($workout['exercise'][$key]);
        $status = "success";
    }
}

$_SESSION['workout'] = $workout;

if ($workout_id == $current_workout_id) {
    header("Location: /staff/wnmp/workouts/create/index.php?id=$current_workout_id");
    exit();
}

header("Location: /staff/wnmp/workouts/index.php?");
exit();