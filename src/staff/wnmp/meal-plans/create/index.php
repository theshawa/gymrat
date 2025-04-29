<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MealPlan.php";
require_once "../../../../db/models/Meal.php";

$mealPlan = new MealPlan();
if (isset($_SESSION['mealPlan'])) {
    $mealPlan = unserialize($_SESSION['mealPlan']);
} else {
    $mealPlan->fill([]);
    $_SESSION['mealPlan'] = serialize($mealPlan);
}

if (!isset($_SESSION['mealTitles'])){    
    $mealModel = new Meal();
    $mealTitles = $mealModel->get_all_titles();
    $_SESSION['mealTitles'] = $mealTitles;
} else {
    $mealTitles = $_SESSION['mealTitles'];
}

$sidebarActive = 5;
$menuBarConfig = [
    "title" => "Create Meal Plan",
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meal-plans/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", 
         "buttonName" => "action", "buttonValue" => "create", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", 
        "buttonName" => "action", "buttonValue" => "revert", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal-plans.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="base-container">
        <div class="form">
            <form action="create_mealplan.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <input type="hidden" name="mealplan_id" value="<?= $mealPlan->id ?>">
                    <h2><label for="edit-title">Title</label></h2>
                    <input type="text" id="edit-title" name="mealplan_name"
                        class="staff-input-primary staff-input-long" value="<?= $mealPlan->name ?>"
                        placeholder="Enter meal plan title">
                    <h2 style="padding-top: 5px;"><label for="edit-description">Description</label></h2>
                    <textarea id="edit-description" name="mealplan_description"
                        class="staff-textarea-primary staff-textarea-large"
                        placeholder="Enter a meal plan description"><?= $mealPlan->description ?></textarea>
                    <h2><label for="edit-duration">Duration</label></h2>
                    <input type="text" id="edit-duration" name="mealplan_duration" pattern="\d+"
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
                            <div class="edit-mealplan-input-reps-sets">
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
                            <div class="edit-mealplan-input-reps-sets">
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
                                <!-- Delete -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f05050" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <button type="submit" name="action" value="add" class="staff-btn-secondary-black edit-meal-plan-add-meal">
                    + Add Meal
                </button>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
