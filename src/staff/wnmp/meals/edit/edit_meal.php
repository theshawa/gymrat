<?php

session_start();

require_once "../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meals");
}
if ($_POST['meal_id'] !== $_SESSION['meal_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/meals/edit?id=" . $_SESSION['meal_id']);
}

$id = $_POST['meal_id'];
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
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/meals/edit?id=" . $id);
}

require_once "../../../../db/models/Meal.php";

$newMeal = new Meal();
$newMeal->fill([
    'id' => $id,
    'name' => $name,
    'description' => $description,
    'calories' => $calories,
    'proteins' => $proteins,
    'fats' => $fats
]);

try {
    $newMeal->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        redirect_with_error_alert("Failed to edit meal due to an error: Meal with the same name already exists", "/staff/wnmp/meals/edit?id=" . $id);
    }
    redirect_with_error_alert("Failed to update meal due to an error: " . $e->getMessage(), "/staff/wnmp/meals/edit?id=" . $id);
}

unset($_SESSION['meal']);
unset($_SESSION['meal_id']);

redirect_with_success_alert("Meal updated successfully", "/staff/wnmp/meals/view?id=" . $id);
