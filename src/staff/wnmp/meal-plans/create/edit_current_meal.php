<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}

if (!isset($_SESSION['mealPlan'])) {
    redirect_with_error_alert("Session variables not set", "/staff/wnmp/meal-plans");
}

$mealPlan = unserialize($_SESSION['mealPlan']);
$current_meal_edit_id = htmlspecialchars($_POST['meal_edit_id']);

foreach ($mealPlan->meals as $key => $meal) {
    if ($meal['edit_id'] == $current_meal_edit_id) {
        $mealPlan->meals[$key]['title'] = htmlspecialchars($_POST['meal_title']);
        $mealPlan->meals[$key]['day'] = htmlspecialchars($_POST['meal_day']);
        $mealPlan->meals[$key]['time'] = htmlspecialchars($_POST['meal_time']);
        $mealPlan->meals[$key]['isUpdated'] = true;
    }
}

$_SESSION['mealPlan'] = serialize($mealPlan);

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/meal-plans/create/index.php");
?>
