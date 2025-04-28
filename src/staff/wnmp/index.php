<?php
require_once "../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

require_once "../../alerts/functions.php";
require_once "../../db/models/WorkoutRequest.php";
require_once "../../db/models/MealPlanRequest.php";
require_once "../../db/models/Settings.php";
require_once "../../db/models/Workout.php";
require_once "../../db/models/Exercise.php";
require_once "../../db/models/Meal.php";
require_once "../../db/models/MealPlan.php";

$pageTitle = "Home";
$pageStyles = ["./wnmp.css"];
$sidebarActive = 1;
$menuBarConfig = [
    "title" => $pageTitle
];

$settings = new Settings();
try {
    $settings->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
}

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

$totalWorkouts = 0;
$totalMealPlans = 0;
$totalExercises = 0;
$totalMeals = 0;

try {
    $workoutModel = new Workout();
    $mealPlanModel = new MealPlan();
    $exerciseModel = new Exercise();
    $mealModel = new Meal();

    $totalWorkouts = $workoutModel->get_total_count();
    $totalMealPlans = $mealPlanModel->get_total_count();
    $totalExercises = $exerciseModel->get_total_count();
    $totalMeals = $mealModel->get_total_count();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to retrieve total counts: " . $e->getMessage();
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
            <?php if ($settings->gym_banner): ?>
                <div class="dashboard-tab-large alt" 
                style="position: relative; display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center; width: 100%; height: 100%;">
                    <style>
                        .dashboard-tab-large.alt::before {
                            content: "";
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: url('../../uploads/<?= $settings->gym_banner ?>') center center / cover no-repeat;
                            filter: blur(8px) brightness(0.5);
                            z-index: -1;
                        }
                    </style>
                <?php else: ?>
                <div class="dashboard-tab-large" 
                style="display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center;">
                <?php endif; ?>

                    <?php if ($settings->show_widgets): ?>
                        <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                            <h1 class="font-zinc-200" style="font-size: 28px;"><?= $settings->gym_name ?></h1>
                        </div>
                    <?php endif; ?>

                    <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                        <h1 class="font-zinc-200">Welcome Back, WNMP Manager!</h1>
                    </div>
                </div>
                <div class="dashboard-tab-large" style="display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center;">

                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; 
                    display: flex; flex-direction: row;">
                        <div style="display: grid; grid-template-rows: 1fr 1fr">
                            <div style="grid-row: 1; justify-self: start; justify-items: center;">
                                <h1 class="font-zinc-200" style="font-size: 28px;"><?= $totalExercises ?></h1>
                                <p class="fint-zinc-200">Exercises</p>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-rows: 1fr 1fr; padding: 0 0 0 20px;">
                            <div style="grid-row: 1; justify-self: start; justify-items: center;">
                                <h1 class="font-zinc-200" style="font-size: 28px;"><?= $totalWorkouts ?></h1>
                                <p class="fint-zinc-200">Workouts</p>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-rows: 1fr 1fr; padding: 0 0 0 20px;">
                            <div style="grid-row: 1; justify-self: start; justify-items: center;">
                                <h1 class="font-zinc-200" style="font-size: 28px;"><?= $totalMeals ?></h1>
                                <p class="fint-zinc-200">Meals</p>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-rows: 1fr 1fr; padding: 0 0 0 20px;">
                            <div style="grid-row: 1; justify-self: start; justify-items: center;">
                                <h1 class="font-zinc-200" style="font-size: 28px;"><?= $totalMealPlans ?></h1>
                                <p class="fint-zinc-200">Meal Plans</p>
                            </div>
                        </div>
                    </div>
                    <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                        <h1 class="font-zinc-200">Currently Managing</h1>
                    </div>
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