<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}

$mealPlan = unserialize($_SESSION['mealPlan']);

try {
    $mealPlan->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to create meal plan due to an error: Meal plan with the same name already exists", "/staff/wnmp/meal-plans/create");
    }
    redirect_with_error_alert("Failed to create meal plan due to an error: " . $e->getMessage(), "/staff/wnmp/meal-plans/create");
}

if (!isset($_SESSION['mealTitles'])){    
    $mealModel = new Meal();
    $mealTitles = $mealModel->get_all_titles();
} else {
    $mealTitles = $_SESSION['mealTitles'];
}

foreach ($mealPlan->meals as &$meal) {
    if (isset($meal['title'])) {
        $mealId = array_search($meal['title'], $mealTitles);
        if ($mealId === false) {
            redirect_with_error_alert("Meal name not found: " . $meal['title'], "/staff/wnmp/meal-plans/create");
        }
        $meal['meal_id'] = $mealId;
        $meal['mealplan_id'] = $mealPlan->id;
    }
}

try {
    $mealPlan->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update meals due to an error: " . $e->getMessage(), "/staff/wnmp/meal-plans/create");
}

redirect_with_success_alert("Meal plan created successfully", "/staff/wnmp/meal-plans/view?id=" . $mealPlan->id);
?>
