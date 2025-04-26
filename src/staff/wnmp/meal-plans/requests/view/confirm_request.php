<?php
// File: src/staff/wnmp/meal-plans/requests/view/confirm_request.php
session_start();

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/MealPlanRequest.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/staff/wnmp/meal-plans");
    exit;
}

$id = htmlspecialchars($_POST['id']);
$confirmation_meal_plan = htmlspecialchars($_POST['confirmation_meal_plan']);

if (empty($confirmation_meal_plan)) {
    redirect_with_error_alert("No Meal Plan is chosen for confirmation", "/staff/wnmp/meal-plans/requests/view?id=" . $id);
    exit;
}

$mealPlanRequest = new MealPlanRequest();
try {
    $mealPlanRequest->get_by_id($id);
    $mealPlanRequest->confirm_request();
} catch (PDOException $e) {
    redirect_with_error_alert("Failed to confirm meal plan request due to an error: " . $e->getMessage(), "/staff/wnmp/meal-plans/requests?filter=1");
    exit;
}

require_once "../../../../../notifications/functions.php";
try {
    notify_trainer($mealPlanRequest->trainerId, "Meal Plan Request Acknowledged", "The Meal Plan you have requested has been created: " . $confirmation_meal_plan, "wnmp manager");
} catch (\Throwable $th) {
    redirect_with_info_alert("Confirmation successful, but failed to send notification: " . $th->getMessage(), "/staff/wnmp/meal-plans/requests?filter=1");
    exit;
}

redirect_with_success_alert("Meal plan request confirmed successfully", "/staff/wnmp/meal-plans/requests?filter=1");
exit;
?>