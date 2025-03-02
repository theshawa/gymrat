<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}

if (!isset($_SESSION['mealPlan'], $_SESSION['mealplan_id'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/meal-plans");
}

$mealPlan = unserialize($_SESSION['mealPlan']);
$current_mealplan_id = $_SESSION['mealplan_id'];
$lastMeal = end($mealPlan->meals);
$edit_id = $lastMeal ? $lastMeal["edit_id"] + 1 : 0;

$newMeal = [
    "id" => 0,
    'mealplan_id' => $current_mealplan_id,
    "meal_id" => 2147483647,
    "title" => "Meal",
    "day" => "",
    "time" => "",
    "isUpdated" => true,
    "isDeleted" => false,
    "edit_id" => $edit_id
];

$mealPlan->meals[] = $newMeal;

$_SESSION['mealPlan'] = serialize($mealPlan);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/meal-plans/edit?id=$current_mealplan_id");
