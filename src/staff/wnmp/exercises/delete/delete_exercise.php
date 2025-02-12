<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/exercises");
}

$id = $_POST['exercise_id'];

require_once "../../../../db/models/Exercise.php";

$exercise = new Exercise();
$exercise->get_by_id($id);

try {
    $exercise->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete exercise due to an error: " . $e->getMessage(), "/staff/wnmp/exercises/view?id=" . $id);
}

unset($_SESSION['exercise']);
unset($_SESSION['exercise_id']);

redirect_with_success_alert("Exercise deleted successfully", "/staff/wnmp/exercises");