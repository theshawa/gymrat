<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Meal.php";
require_once "../../../../uploads.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meals");
    exit;
}

$name = $_POST['meal_name'];
$description = $_POST['meal_description'];
$calories = $_POST['meal_calories'];
$proteins = $_POST['meal_proteins'];
$fats = $_POST['meal_fats'];

// Validation
$errors = [];

if (empty($name)) $errors[] = "Name is required.";
if (empty($description)) $errors[] = "Description is required.";
// if (empty($calories)) $errors[] = "Calories are required.";
// if (empty($proteins)) $errors[] = "Proteins are required.";
// if (empty($fats)) $errors[] = "Fats are required.";

// image upload
$image = $_FILES['meal_image']['name'] ? $_FILES['meal_image'] : null;
if ($image) {
    try {
        $image = upload_file("staff-meal-images", $image);
    } catch (\Throwable $th) {
        redirect_with_error_alert("Failed to upload image due to an error: " . $th->getMessage(), "/staff/wnmp/meals/create");
        exit;
    }
}


$meal = unserialize($_SESSION['meal']);

// check for existing image and delete if found
if ($meal->image && $image) {
    $old_image = $meal->image;
    try {
        delete_file($old_image);
    } catch (\Throwable $th) {
        $_SESSION['error'] = "Failed to delete existing image due to an error: " . $th->getMessage();
    }
    $meal->image = $image;
}


$meal->name = $name;
$meal->description = $description;
$meal->calories = $calories;
$meal->proteins = $proteins;
$meal->fats = $fats;
$meal->image = $image ?? $meal->image;


if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/meals/create");
    exit;
}


$_SESSION['meal'] = serialize($meal);

try {
    $meal->save();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['meal']->name = "";
        redirect_with_error_alert("Failed to create meal due to an error: Meal with the same name already exists", "/staff/wnmp/meals/create");
        exit;
    }
    redirect_with_error_alert("Failed to create meal due to an error: " . $e->getMessage(), "/staff/wnmp/meals/create");
    exit;
}

unset($_SESSION['meal']);

redirect_with_success_alert("Meal created successfully", "/staff/wnmp/meals");
exit;
