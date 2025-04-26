<?php
// File: src/trainer/meal-plans/request/request_process.php
require_once "../../../../../auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once "../../../../../alerts/functions.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "./");
    exit;
}

$description = trim(htmlspecialchars($_POST['description']));
$client_goal = htmlspecialchars($_POST['client_goal']);
$priority_level = htmlspecialchars($_POST['priority_level'] ?? 'normal');

// Validate inputs
if (empty($description)) {
    redirect_with_error_alert("Please provide a description for the meal plan", "./");
    exit;
}

if (empty($client_goal)) {
    redirect_with_error_alert("Please select a goal for the client", "./");
    exit;
}

// Format the description with the goal and priority info
$formatted_description =
    "Goal: " . ucfirst(str_replace('_', ' ', $client_goal)) . "\n" .
    "Priority: " . ucfirst($priority_level) . "\n\n" .
    $description;

try {
    // Use the Database class directly since the MealPlanRequest model doesn't have a create method
    require_once "../../../../../db/Database.php";
    $conn = Database::get_conn();

    $sql = "INSERT INTO mealplan_requests (trainer_id, customer_id, description) VALUES (:trainer_id, :customer_id, :description)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'trainer_id' => $_SESSION['auth']['id'],
        'customer_id' => $_POST['customer_id'], // Make sure this is available in your form
        'description' => $formatted_description

    ]);

    redirect_with_success_alert("Meal plan request submitted successfully. Our nutritionists will create it soon.", "../");
    exit;
} catch (Exception $e) {
    redirect_with_error_alert("Failed to submit request: " . $e->getMessage(), "./");
    exit;
}
