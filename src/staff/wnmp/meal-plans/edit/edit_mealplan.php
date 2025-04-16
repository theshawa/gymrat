<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}
if ($_POST['mealplan_id'] !== $_SESSION['mealplan_id']) {
    redirect_with_error_alert("ID mismatch occurred", "/staff/wnmp/meal-plans/edit?id=" . $_SESSION['mealplan_id']);
}

$mealPlan_id = htmlspecialchars($_POST['mealplan_id']);
$mealPlan = unserialize($_SESSION['mealPlan']);
if (!$mealPlan) {
    redirect_with_error_alert("Failed to load meal plan from session", "/staff/wnmp/meal-plans");
}
$errors = [];


// Handle mealplan details update
if (isset($_POST['mealplan_name'], $_POST['mealplan_description'], $_POST['mealplan_duration'])) {
    $name = htmlspecialchars($_POST['mealplan_name']);
    $description = htmlspecialchars($_POST['mealplan_description']);
    $duration = htmlspecialchars($_POST['mealplan_duration']);

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($duration)) $errors[] = "Duration is required.";

    $mealPlan->name = $name;
    $mealPlan->description = $description;
    $mealPlan->duration = $duration;
}


// Handle mealplan updates
if (!empty($mealPlan->meals)) {
    foreach ($mealPlan->meals as $key => $meal) {
        $edit_id = $meal['edit_id'];
       
        $title_key = "meal_title_$edit_id";
        $time_key = "meal_time_$edit_id";
        $day_key = "meal_day_$edit_id";

        if (isset($_POST[$title_key], $_POST[$time_key], $_POST[$day_key])) {
            $mealPlan->meals[$key]['title'] = htmlspecialchars($_POST[$title_key]);
            $mealPlan->meals[$key]['time'] = htmlspecialchars($_POST[$time_key]);
            $mealPlan->meals[$key]['day'] = htmlspecialchars($_POST[$day_key]);
            $mealPlan->meals[$key]['isUpdated'] = true;
        }
    }
}

// Delete Logic
if (isset($_POST['delete_meal'])) {
    $current_meal_edit_id = htmlspecialchars($_POST['delete_meal']);

    foreach ($mealPlan->meals as $key => $meal) {
        if ($meal['edit_id'] == $current_meal_edit_id) {
            $mealPlan->meals[$key]['isDeleted'] = true;
        }
    } 
} 


// Add Logic
if (isset($_POST['action']) && $_POST['action'] === 'add'){
    $lastMeal = end($mealPlan->meals);
    $edit_id = $lastMeal ? $lastMeal["edit_id"] + 1 : 0;

    $newMeal = [
        "id" => 0,
        'mealplan_id' => $mealPlan_id,
        "meal_id" => 2147483647,
        "edit_id" => $edit_id,
        "title" => "Meal",
        "day" => "",
        "time" => "",
        "isUpdated" => true,
        "isDeleted" => false
    ];

    $mealPlan->meals[] = $newMeal;
}

// Validation
if (empty($mealPlan->meals) && (!isset($_POST['action']) || $_POST['action'] !== 'add')) {
    $errors[] = "Meal plan must contain at least one meal. [0]";
}

if (!empty($mealPlan->meals)) {
    $allDeleted = true;
    $mealCombinationTracker = [];
    foreach ($mealPlan->meals as $meal) {
        if (empty($meal['isDeleted']) || !$meal['isDeleted']) {
            $allDeleted = false;

            // Check for duplicate meals
            $combinationKey = $meal['title'] . '|' . $meal['time'] . '|' . $meal['day'];
            if (isset($mealCombinationTracker[$combinationKey])) {
                $errors[] = "Duplicate meal found: Title '{$meal['title']}', Time '{$meal['time']}', Day '{$meal['day']}'.";
            } else {
                $mealCombinationTracker[$combinationKey] = true;
            }
        }
    }
    if ($allDeleted) {
        $errors[] = "Meal plan must contain at least one meal. [1]";
    }
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/meal-plans/edit?id=" . $mealPlan_id);
}


// Revert Logic
if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    $mealPlan = new MealPlan();
    $mealModel = new Meal();
    try {
        $mealPlan->get_by_id($mealPlan_id);
        $mealPlan->meals = $mealModel->addMealTitles($mealPlan->meals);
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch meal: " . $e->getMessage(), "/staff/wnmp");
    }
}



$_SESSION['mealPlan'] = serialize($mealPlan);
// echo '<pre>';
// print_r($mealPlan);
// echo '</pre>';


// Create Logic
if (isset($_POST['action']) && $_POST['action'] === 'edit'){
    
    $mealModel = new Meal();
    $mealTitles = $mealModel->get_all_titles();

    foreach ($mealPlan->meals as &$meal) {
        if (isset($meal['title']) && $meal['isUpdated'] && !$meal['isDeleted']) {
            $mealId = array_search($meal['title'], $mealTitles);
            if ($mealId === false) {
                redirect_with_error_alert("Meal name not found: " . $meal['title'], "/staff/wnmp/meal-plans/edit?id=" . $mealPlan_id);
            }
            $meal['meal_id'] = $mealId;
        }
    }

    try {
        $mealPlan->save();
    } catch (PDOException $e) {
        redirect_with_error_alert("Failed to update meal plan due to an error: " . $e->getMessage(), "/staff/wnmp/meal-plans/edit?id=" . $mealPlan_id);
    }

    unset($_SESSION['mealPlan']);
    unset($_SESSION['mealplan_id']);
    
    redirect_with_success_alert("Meal plan created successfully", "/staff/wnmp/meal-plans/view?id=" . $mealPlan->id);
}


redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/meal-plans/edit?id=" . $mealPlan->id);

?>
