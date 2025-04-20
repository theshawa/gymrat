<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../uploads.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/exercises");
    exit;
}

$id = $_POST['exercise_id'];

require_once "../../../../db/models/Exercise.php";

$exercise = new Exercise();
$exercise->get_by_id($id);
$image = $exercise->image;

try {
    $exercise->delete();
} catch (PDOException $e) {
    if ($e->getCode() === "23000" && strpos($e->getMessage(), '1451') !== false) {
        redirect_with_error_alert("Failed to delete exercise because it is associated with workouts. Please remove the associations first.", "/staff/wnmp/exercises/view?id=" . $id);
        exit;
    }
    redirect_with_error_alert("Failed to delete exercise due to an error: " . $e->getMessage(), "/staff/wnmp/exercises/view?id=" . $id);
    exit;
}

if ($image) {
    try {
        delete_file($image);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete exercise image due to an error: " . $th->getMessage();
    }
}

unset($_SESSION['exercise']);
unset($_SESSION['exercise_id']);

redirect_with_success_alert("Exercise deleted successfully", "/staff/wnmp/exercises");
exit;