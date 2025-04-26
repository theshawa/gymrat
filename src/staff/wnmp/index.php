<?php
require_once "../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

require_once "../../alerts/functions.php";
require_once "../../db/models/WorkoutRequest.php";
require_once "../../db/models/MealPlanRequest.php";

$pageTitle = "Home";
$pageStyles = ["./wnmp.css"];
$sidebarActive = 1;
$menuBarConfig = [
    "title" => $pageTitle
];


$setWorkoutRequestsNotification = null;
$setMealPlanRequestsNotification = null;

$workoutRequestModel = new WorkoutRequest();
try {
    $setWorkoutRequestsNotification = $workoutRequestModel->has_unreviewed_requests();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access workout requests: " . $e->getMessage();
}

$mealPlanRequestModel = new MealPlanRequest();
try {
    $setMealPlanRequestsNotification = $mealPlanRequestModel->has_unreviewed_requests();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access meal plan requests: " . $e->getMessage();
}

require_once "./pageconfig.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../includes/menubar.php"; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-col-primary">
                <div class="dashboard-tab-large dashboard-layout-primary">
                    <h1>Welcome, WNMP Manager!</h1>
                </div>
                <div class="dashboard-tab-large dashboard-layout-primary">
                    <h1>Some random info here</h1>
                </div>
            </div>
            <div class="dashboard-col-secondary">
                <a a href="/staff/wnmp/exercises/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Exercises</h1>
                </a>
                <a href="/staff/wnmp/meals/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Meals</h1>
                </a>
                <a href="/staff/wnmp/workouts/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Workouts</h1>
                </a>
                <a href="/staff/wnmp/meal-plans/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Meal Plans</h1>
                </a>
                <a href="/staff/wnmp/workouts/requests/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start;">
                        <?php if ($setWorkoutRequestsNotification): ?>
                            <span class="dashboard-alert-red-dot"></span>
                        <?php endif; ?>
                    </div>
                    <h1>Workout Requests</h1>
                </a>
                <a href="/staff/wnmp/meal-plans/requests/index.php" class="dashboard-tab-small dashboard-layout-primary">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start;">
                        <?php if ($setMealPlanRequestsNotification): ?>
                            <span class="dashboard-alert-red-dot"></span>
                        <?php endif; ?>
                    </div>
                    <h1>Meal Plan Requests</h1>
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>