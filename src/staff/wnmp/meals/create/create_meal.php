<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meals");
}

$name = $_POST['meal_name'];
$description = $_POST['meal_description'];
$calories = $_POST['meal_calories'];
$proteins = $_POST['meal_proteins'];
$fats = $_POST['meal_fats'];

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}
if (empty($description)) {
    $errors[] = "Description is required.";
}
if (empty($calories)) {
    $errors[] = "Calories are required.";
}
if (empty($proteins)) {
    $errors[] = "Proteins are required.";
}
if (empty($fats)) {
    $errors[] = "Fats are required.";
}

if (!empty($errors)) {
    $_SESSION['meal'] = $_POST;
    $error_message = implode(" ", $errors);
    redirect_with_error_alert("Error: " .  $error_message, "/staff/wnmp/meals/create");
}

require_once "../../../../db/models/Meal.php";

$newMeal = new Meal();
$newMeal->fill([
    'name' => $name,
    'description' => $description,
    'calories' => $calories,
    'proteins' => $proteins,
    'fats' => $fats
]);

try {
    $newMeal->save();
} catch (PDOException $e) {
    $_SESSION['meal'] = $newMeal;
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['meal']->name = "";
        redirect_with_error_alert("Failed to create meal due to an error: Meal with the same name already exists", "/staff/wnmp/meals/create");
    }
    redirect_with_error_alert("Failed to create meal due to an error: " . $e->getMessage(), "/staff/wnmp/meals/create");
}

unset($_SESSION['meal']);

redirect_with_success_alert("Meal created successfully", "/staff/wnmp/meals");
