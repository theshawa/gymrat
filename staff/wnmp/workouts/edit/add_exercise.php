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
$lastExercise = end($workout["exercise"]);

$newExercise = [
    "id" => $lastExercise['id'] + 1,
    "title" => "Exercise",
    "sets" => 0,
    "reps" => 0
];

$workout["exercise"][] = $newExercise;

$_SESSION['workout'] = $workout;

if ($workout_id == $current_workout_id) {
    header("Location: /staff/wnmp/workouts/edit/index.php?id=$current_workout_id");
    exit();
}

header("Location: /staff/wnmp/workouts/index.php?");
exit();

