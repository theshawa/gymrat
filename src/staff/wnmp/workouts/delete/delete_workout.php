<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
}
if ($_POST['workout_id'] !== $_SESSION['workout_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/workouts/delete?id=" . $_SESSION['workout_id']);
}

$id = htmlspecialchars($_POST['workout_id']);


try {
    $workout->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete workout due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/delete?id=" . $id);
}

unset($_SESSION['workout']);
unset($_SESSION['workout_id']);

redirect_with_success_alert("Workout deleted successfully", "/staff/wnmp/workouts");
?>
