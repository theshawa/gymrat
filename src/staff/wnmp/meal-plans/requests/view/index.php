<?php
// session_start();

$id = $_GET['id'] ?? null;

$sidebarActive = 5;

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/Trainer.php";
require_once "../../../../../db/models/MealPlanRequest.php";
require_once "../../../../../db/models/MealPlan.php";

$mealPlanRequest = new MealPlanRequest();
try {
    $trainerModel = new Trainer();
    $mealPlanRequest->get_by_id($id);
    $mealPlanRequest->trainer = $trainerModel->get_username_by_id($mealPlanRequest->trainerId);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal plan request: " . $e->getMessage(), "/staff/wnmp/meal-plans/requests");
}

$mealPlanTitles = [];
try {
    $mealPlanModel = new MealPlan();
    $mealPlanTitles = $mealPlanModel->get_all_titles();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch meal plan titles: " . $e->getMessage();
}

$menuBarConfig = [
    "title" => "Meal Plan Request #" . $mealPlanRequest->id,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meal-plans/requests/index.php?filter=1",
];

require_once "../../../pageconfig.php";

require_once "../../../../includes/header.php";
require_once "../../../../includes/sidebar.php";

require_once "../../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");
?>

<main>
<div class="staff-base-container">
        <?php require_once "../../../../includes/menubar.php"; ?>
        <div class="staff-base-sub-container-alt">
            <div>
                <h2 style="margin-bottom: 10px;">
                    Description
                </h2>
                <p><?= $mealPlanRequest->description ?></p>
                <div style="display: flex; flex-direction: row; margin-top: 20px; align-items: center; ">
                    <h2>
                        Trainer : 
                    </h2>
                    <p>&emsp;<?= $mealPlanRequest->trainer ?></p>
                </div>
            </div>
            <?php if ($mealPlanRequest->reviewed == 0): ?>
            <form action="confirm_request.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                <input type="hidden" name="id" value="<?= $mealPlanRequest->id ?>">
                <h2>
                    Acknowledge Request
                </h2>
                <p>Select relevant meal plan to confirm the successful creation of the requested meal plan</p>
                <div style = "display: flex; flex-direction: row; gap: 20px; align-items: center;">
                <select name="confirmation_meal_plan" class="staff-input-primary staff-input-long">
                    <?php foreach ($mealPlanTitles as $title): ?>
                        <option value="<?= $title ?>">
                            <?= $title ?>
                        </option>
                    <?php endforeach; ?>
                </select>    
                <button type="submit" class="staff-button secondary" style="min-height: 38px; min-width:120px; margin-top: 5px;">Confirm</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once "../../../../includes/footer.php"; ?>
