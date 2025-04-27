<?php
// File: src/trainer/customers/profile/meal-plan/request/request_process.php
$root_path = $_SERVER['DOCUMENT_ROOT'];
require_once $root_path . "/auth-guards.php";
if (auth_required_guard("trainer", "/trainer/login"))
    exit;

require_once $root_path . "/alerts/functions.php";

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
    require_once $root_path . "/db/Database.php";
    $conn = Database::get_conn();

    // Silently handle missing customer_id without showing error alert
    if (empty($_POST['customer_id'])) {
        // Redirect back without showing error
        header("Location: ./");
        exit;
    }

    $sql = "INSERT INTO mealplan_requests (trainer_id, customer_id, description) VALUES (:trainer_id, :customer_id, :description)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'trainer_id' => $_SESSION['auth']['id'],
        'customer_id' => $_POST['customer_id'],
        'description' => $formatted_description
    ]);

    redirect_with_success_alert("Meal plan request submitted successfully. Our nutritionists will create it soon.", "../?id=" . $_POST['customer_id']);
    exit;
} catch (Exception $e) {
    redirect_with_error_alert("Failed to submit request: " . $e->getMessage(), "./");
    exit;
}
