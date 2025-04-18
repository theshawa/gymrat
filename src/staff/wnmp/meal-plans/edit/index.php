<?php
session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";

$id = htmlspecialchars($_GET['id'] ?? null);
$_SESSION['mealplan_id'] = $id;

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

if (!isset($_SESSION['mealTitles'])) {    
    $mealModel = new Meal();
    $mealTitles = $mealModel->get_all_titles();
    $_SESSION['mealTitles'] = $mealTitles;
} else {
    $mealTitles = $_SESSION['mealTitles'];
}

$sidebarActive = 5;
$menuBarConfig = [
    "title" => "Edit " . ($mealPlan->name ?? "Unnamed Meal Plan"),
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meal-plans/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", 
        "buttonName" => "action", "buttonValue" => "edit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", 
        "buttonName" => "action", "buttonValue" => "revert", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal-plans.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");
?>

<main>
    <div class="base-container">
        <div class="form">
            <form action="edit_mealplan.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <!-- <pre><?php print_r($mealPlan->meals); ?></pre> -->
                <div style="padding: 5px 10px;">
                    <input type="hidden" name="mealplan_id" value="<?= $mealPlan->id ?>">
                    <h2><label for="edit-title">Title</label></h2>
                    <input type="text" id="edit-title" name="mealplan_name"
                        class="staff-input-primary staff-input-long" value="<?= $mealPlan->name ?>">
                    <h2><label for="edit-description">Description</label></h2>
                    <textarea id="edit-description" name="mealplan_description"
                        class="staff-textarea-primary staff-textarea-large"
                        placeholder="Enter a meal plan description"><?= $mealPlan->description ?></textarea>
                    <h2><label for="edit-duration">Duration</label></h2>
                    <input type="text" id="edit-duration" name="mealplan_duration"
                        class="staff-input-primary staff-input-long" value="<?= $mealPlan->duration ?>">
                </div>
                <div style="padding: 5px 10px;">
                    <h2>Meals</h2>
                    <?php foreach ($mealPlan->meals as $meal): ?>
                        <?php if (!$meal['isDeleted']): ?>
                            <div class="edit-meal-plan-row">
                                <select name="meal_title_<?= $meal['edit_id'] ?>" class="staff-input-primary staff-input-long">
                                    <?php foreach ($mealTitles as $title): ?>
                                        <option value="<?= $title ?>" <?= $title == $meal['title'] ? 'selected' : '' ?>>
                                            <?= $title ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="edit-meal-plan-input-reps-sets">
                                    <label for="meal_day_<?= $meal['edit_id'] ?>">Day</label>
                                    <select name="meal_day_<?= $meal['edit_id'] ?>" class="staff-input-primary staff-input-short">
                                        <option value="Monday" <?= $meal['day'] == 'Monday' ? 'selected' : '' ?>>Monday</option>
                                        <option value="Tuesday" <?= $meal['day'] == 'Tuesday' ? 'selected' : '' ?>>Tuesday</option>
                                        <option value="Wednesday" <?= $meal['day'] == 'Wednesday' ? 'selected' : '' ?>>Wednesday</option>
                                        <option value="Thursday" <?= $meal['day'] == 'Thursday' ? 'selected' : '' ?>>Thursday</option>
                                        <option value="Friday" <?= $meal['day'] == 'Friday' ? 'selected' : '' ?>>Friday</option>
                                        <option value="Saturday" <?= $meal['day'] == 'Saturday' ? 'selected' : '' ?>>Saturday</option>
                                        <option value="Sunday" <?= $meal['day'] == 'Sunday' ? 'selected' : '' ?>>Sunday</option>
                                    </select>
                                </div>
                                <div class="edit-meal-plan-input-reps-sets">
                                    <label for="meal_time_<?= $meal['edit_id'] ?>">Time</label>
                                    <select name="meal_time_<?= $meal['edit_id'] ?>" class="staff-input-primary staff-input-short">
                                        <option value="Breakfast" <?= $meal['time'] == 'Breakfast' ? 'selected' : '' ?>>Breakfast</option>
                                        <option value="Lunch" <?= $meal['time'] == 'Lunch' ? 'selected' : '' ?>>Lunch</option>
                                        <option value="Dinner" <?= $meal['time'] == 'Dinner' ? 'selected' : '' ?>>Dinner</option>
                                        <option value="Snack" <?= $meal['time'] == 'Snack' ? 'selected' : '' ?>>Snack</option>
                                    </select>
                                </div>
                                <button type="submit" class="staff-btn-outline edit-meal-plan-input-delete"
                                    name="delete_meal" value="<?= $meal['edit_id'] ?>">
                                    Delete
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="add" class="staff-btn-secondary-black edit-meal-plan-add-meal">
                        + Add Meal
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
