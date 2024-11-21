<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

// ----------------
$workout = &$_SESSION['workout'];
//$action = htmlspecialchars($_POST['action']);
// var_dump($workout['exercise']);
echo "\n";
var_dump($_POST);

// make overall changes to the workout
// grab exercise data from current session


// if (isset($_POST['deleteExercise']) && isset($_POST['exercise-id'])) {
//     $exerciseId = htmlspecialchars($_POST['exercise-id']);
//     var_dump($exerciseId);
//     echo "\n";
//     foreach ($workout['exercise'] as $key => $exercise) {
//         if ($exercise['id'] == $exerciseId) {
//             unset($workout['exercise'][$key]);
//             break;
//         }
//     }
//     $_SESSION['workout'] = $workout;
//     var_dump($_SESSION['workout']);
//     echo "\n";
// }

//header("Location: /staff/wnmp/workouts/edit/index.php");
//exit();