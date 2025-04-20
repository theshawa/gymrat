<?php

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$id = htmlspecialchars($_GET['id'] ?? null);

require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";
require_once "../../../../alerts/functions.php";

$mealPlan = new MealPlan();
$meal = new Meal();
try {
    $mealPlan->get_by_id($id);
    $mealPlan->meals = $meal->addMealTitles($mealPlan->meals);
    $_SESSION['mealPlan'] = serialize($mealPlan);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal plan: " . $e->getMessage(), "/staff/wnmp/meal-plans");
    exit;
}

$sidebarActive = 5;
$menuBarConfig = [
    "title" => $mealPlan->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meal-plans/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Meal Plan", "href" => "/staff/wnmp/meal-plans/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Meal Plan", "href" => "/staff/wnmp/meal-plans/delete/index.php?id=$id", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal-plans.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="view-meal-plan-container">
            <div>
                <h2 style="margin-bottom: 20px;">
                    Meals
                </h2>
                <?php foreach ($mealPlan->meals as $meal): ?>
                    <div class="view-meal-plan-meal">
                        <p><?= $meal['title'] ?></p>
                        <p class="alt"><?= $meal['day'] ?> - <?= $meal['time'] ?></p>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
            <div>
                <h2 style="margin-bottom: 20px;">
                    Description
                </h2>
                <p><?= $mealPlan->description ?></p>
                <h2 style="margin-bottom: 10px; margin-top: 25px">
                    Duration
                </h2>
                <p style="font-size: 18px; font-weight: 400;"><?= $mealPlan->duration ?> Days</p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
