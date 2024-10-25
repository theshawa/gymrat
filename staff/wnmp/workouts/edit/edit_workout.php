<?php


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

// -----FOR TESTING------
//if (!isset($_SESSION['workout'])) {
//    $_SESSION['workout'] = [
//        [
//            "id" => 001,
//            "title" => "Squats",
//            "sets" => 3,
//            "reps" => 10
//        ],
//        [
//            "id" => 002,
//            "title" => "Deadlifts",
//            "sets" => 3,
//            "reps" => 10
//        ],
//        [
//            "id" => 003,
//            "title" => "Bench Press",
//            "sets" => 3,
//            "reps" => 10
//        ],
//        [
//            "id" => 004,
//            "title" => "Pull-Ups",
//            "sets" => 3,
//            "reps" => 10
//        ],
//        [
//            "id" => 005,
//            "title" => "Overhead Press",
//            "sets" => 3,
//            "reps" => 10
//        ],
//        [
//            "id" => 006,
//            "title" => "Quads",
//            "sets" => 3,
//            "reps" => 10
//        ],
//        [
//            "id" => 007,
//            "title" => "Dumbbell Rows",
//            "sets" => 3,
//            "reps" => 10
//        ]
//    ];
//}
// ----------------
$workout = &$_SESSION['workout'];
//$action = $_POST['action'];
var_dump($workout);
echo "\n";


if (isset($_POST['deleteExercise']) && isset($_POST['exercise-id'])) {
    $exerciseId = $_POST['exercise-id'];
    var_dump($exerciseId);
    echo "\n";
    foreach ($workout['exercise'] as $key => $exercise) {
        if ($exercise['id'] == $exerciseId) {
            unset($workout['exercise'][$key]);
            break;
        }
    }
    $_SESSION['workout'] = $workout;
    var_dump($_SESSION['workout']);
    echo "\n";
}

//header("Location: /staff/wnmp/workouts/edit/index.php");
//exit();