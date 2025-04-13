<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meals");
}

$id = $_POST['meal_id'];

require_once "../../../../db/models/Meal.php";

$meal = new Meal();
$meal->get_by_id($id);

try {
    $meal->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete meal due to an error: " . $e->getMessage(), "/staff/wnmp/meals/view?id=" . $id);
}

unset($_SESSION['meal']);
unset($_SESSION['meal_id']);

redirect_with_success_alert("Meal deleted successfully", "/staff/wnmp/meals");
