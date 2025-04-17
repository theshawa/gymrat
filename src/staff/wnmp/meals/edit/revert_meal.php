<?php

session_start();

$id = &$_SESSION['meal_id'];

require_once "../../../../db/models/Meal.php";

$originalMeal = new Meal();

try {
    $originalMeal->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal: " . $e->getMessage(), "/staff/wnmp");
    exit;
}

$_SESSION['meal'] = serialize($originalMeal);

redirect_with_success_alert("Meal reverted successfully", "/staff/wnmp/meals/edit/?id=" . $id);
exit;
