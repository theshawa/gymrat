<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
}


$mealPlan = unserialize($_SESSION['mealPlan']);
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

// Validation
if (empty($mealPlan->meals)) {
    $errors[] = "Meal plan must contain at least one meal.";
}

if (!empty($errors)) {
    $error_message = implode(" ", $errors);
    redirect_with_error_alert($error_message, "/staff/wnmp/meal-plans/create");
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
        'mealplan_id' => 0,
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


// Revert Logic
if (isset($_POST['action']) && $_POST['action'] === 'revert') {
    $mealPlan = new MealPlan();
    $mealPlan->fill([]);
}


$_SESSION['mealPlan'] = serialize($mealPlan);
// echo '<pre>';
// print_r($mealPlan);
// echo '</pre>';


// Create Logic
if (isset($_POST['action']) && $_POST['action'] === 'create'){
    
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
}

redirect_with_success_alert("Action successful (Press Save Changes to complete)", "/staff/wnmp/meal-plans/create");

?>
