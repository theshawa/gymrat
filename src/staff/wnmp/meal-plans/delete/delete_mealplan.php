<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
    exit;
}
if ($_POST['meal_plan_id'] !== $_SESSION['meal_plan_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/meal-plans/delete?id=" . $_SESSION['meal_plan_id']);
    exit;
}

$id = htmlspecialchars($_POST['meal_plan_id']);

require_once "../../../../db/models/MealPlan.php";

$mealPlan = unserialize($_SESSION['mealPlan']);

if (!$mealPlan) {
    redirect_with_error_alert("Failed to load meal plan from session", "/staff/wnmp/meal-plans");
    exit;
}

try {
    $mealPlan->delete();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to delete meal plan due to an error: " . $e->getMessage(), "/staff/wnmp/meal-plans/delete?id=" . $id);
    exit;
}

unset($_SESSION['mealPlan']);
unset($_SESSION['meal_plan_id']);

redirect_with_success_alert("Meal plan deleted successfully", "/staff/wnmp/meal-plans");
exit;
?>
