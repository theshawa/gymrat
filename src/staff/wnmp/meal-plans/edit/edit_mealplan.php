<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}
if ($_POST['mealplan_id'] !== $_SESSION['mealplan_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/meal-plans/edit?id=" . $_SESSION['mealplan_id']);
}

$id = htmlspecialchars($_POST['mealplan_id']);
$name = htmlspecialchars($_POST['mealplan_name']);
$description = htmlspecialchars($_POST['mealplan_description']);
$duration = htmlspecialchars($_POST['mealplan_duration']);

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}
if (empty($description)) {
    $errors[] = "Description is required.";
}
if (empty($duration)) {
    $errors[] = "Duration is required.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/meal-plans/edit?id=" . $id);
}

require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";

$mealPlan = unserialize($_SESSION['mealPlan']);

if (!$mealPlan) {
    redirect_with_error_alert("Failed to load meal plan from session", "/staff/wnmp/meal-plans");
}

$mealModel = new Meal();
$mealTitles = $mealModel->get_all_titles();

foreach ($mealPlan->meals as &$meal) {
    if (isset($meal['title']) && $meal['isUpdated'] && !$meal['isDeleted']) {
        $mealId = array_search($meal['title'], $mealTitles);
        if ($mealId === false) {
            redirect_with_error_alert("Meal name not found: " . $meal['title'], "/staff/wnmp/meal-plans/edit?id=" . $id);
        }
        $meal['meal_id'] = $mealId;
    }
}

// var_dump($mealPlan->meals);

$mealPlan->id = $id;
$mealPlan->name = $name;
$mealPlan->description = $description;
$mealPlan->duration = $duration;

// var_dump($mealPlan);

try {
    $mealPlan->save();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to update meal plan due to an error: " . $e->getMessage(), "/staff/wnmp/meal-plans/edit?id=" . $id);
}

unset($_SESSION['mealPlan']);
unset($_SESSION['mealplan_id']);

redirect_with_success_alert("Meal plan updated successfully", "/staff/wnmp/meal-plans/view?id=" . $id);
?>
