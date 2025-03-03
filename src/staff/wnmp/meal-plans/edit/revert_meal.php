<?php

session_start();

$id = &$_SESSION['mealplan_id'];

require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";
require_once "../../../../alerts/functions.php";

$mealPlan = new MealPlan();
$mealModel = new Meal();
try {
    $mealPlan->get_by_id($id);
    $mealPlan->meals = $mealModel->addMealTitles($mealPlan->meals);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal: " . $e->getMessage(), "/staff/wnmp");
}

$_SESSION['mealPlan'] = serialize($mealPlan);

redirect_with_success_alert("Meal plan changes reverted", "/staff/wnmp/meal-plans/edit/index.php?id=" . $id);
   