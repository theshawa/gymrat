<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

var_dump($_POST);

// $workout_id = $_POST['workout_id'];
// $exercise_id = $_POST['exercise_id'];

// $current_workout_id = $_SESSION['workout']['id'];

// if ($workout_id != $current_workout_id) {
//     header("Location: /staff/wnmp/workouts/edit/index.php?id=$current_workout_id?err=blablabla");
// }

// make changes to the exercise data in current session
// handles update, delete, and add new exercise