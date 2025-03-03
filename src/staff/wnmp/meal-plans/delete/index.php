<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";

$id = htmlspecialchars($_GET['id'] ?? null);
$_SESSION['meal_plan_id'] = $id;

$mealPlan = new MealPlan();
if (!isset($_SESSION['mealPlan'])) {
    $mealModel = new Meal();
    try {
        $mealPlan->get_by_id($id);
        $mealPlan->meals = $mealModel->addMealTitles($mealPlan->meals);
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch meal plan: " . $e->getMessage(), "/staff/wnmp/meal-plans");
    }
    $_SESSION['mealPlan'] = serialize($mealPlan);
} else {
    $mealPlan = unserialize($_SESSION['mealPlan']);
}

$sidebarActive = 5;
$menuBarConfig = [
    "title" => 'Delete ' . $mealPlan->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meal-plans/view/index.php?id=" . intval($id)
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal-plans.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <form action="delete_mealplan.php" method="post" class="form">
            <input type="hidden" name="meal_plan_id" value="<?= $mealPlan->id ?>">
            <div class="staff-record-delete-div">
                <h2>Are you sure you want to delete "<?= $mealPlan->name ?>"?</h2>
                <p>This action cannot be undone.</p>
                <button type="submit">Delete</button>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
