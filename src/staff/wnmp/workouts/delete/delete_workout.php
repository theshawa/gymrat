<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";
require_once "../../../../db/models/Customer.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/workouts");
    exit;
}
if ($_POST['workout_id'] !== $_SESSION['workout_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/workouts/delete?id=" . $_SESSION['workout_id']);
    exit;
}

$id = htmlspecialchars($_POST['workout_id']);
$workout = new Workout();
$workout->id = $id;

$customerModel = new Customer();
$customers = null;
try {
    $customers = $customerModel->get_customer_ids_by_workout_id($id);
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to get customer ids due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/delete?id=" . $id );
    exit;
}

if ($customers !== null) {
    redirect_with_error_alert("Failed to delete workout since it is already assigned to customers ", "/staff/wnmp/workouts/view?id=" . $id );
    exit;
}

try {
    $workout->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete workout due to an error: " . $e->getMessage(), "/staff/wnmp/workouts/delete?id=" . $id);
    exit;
}

unset($_SESSION['workout']);
unset($_SESSION['workout_id']);

redirect_with_success_alert("Workout deleted successfully", "/staff/wnmp/workouts");
exit;
?>