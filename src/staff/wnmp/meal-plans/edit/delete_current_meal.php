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
$current_meal_edit_id = htmlspecialchars($_POST['meal_edit_id']);
$current_mealplan_id = $_SESSION['mealplan_id'];

foreach ($mealPlan->meals as $key => $meal) {
    if ($meal['edit_id'] == $current_meal_edit_id) {
        $mealPlan->meals[$key]['isDeleted'] = true;
    }
}

$_SESSION['mealPlan'] = serialize($mealPlan);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/meal-plans/edit?id=$current_mealplan_id");
