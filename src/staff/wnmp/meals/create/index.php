<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$sidebarActive = 4;

require_once "../../../../db/models/Meal.php";
require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Create Meal",
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meals/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_meal.php", "type" => "destructive"]
    ]
];

$meal = new Meal();
if (isset($_SESSION['meal'])) {
    $meal = unserialize($_SESSION['meal']);
} else {
    $meal->fill([]);
    $_SESSION['meal'] = serialize($meal);
}

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="create_meal.php" method="POST" enctype="multipart/form-data">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-name">Name</label></h2>
                        <input type="text" id="edit-name" name="meal_name"
                            class="staff-input-primary staff-input-long" value="<?= $meal->name ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-description">Description</label></h2>
                        <textarea id="edit-description" name="meal_description"
                            class="staff-textarea-primary staff-textarea-large"
                            placeholder="Enter a meal description"><?= $meal->description ?></textarea>
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-image">Image</label></h2>
                        <input type="file" id="edit-image" name="meal_image" accept="image/*"
                            class="staff-input-primary staff-input-long">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-calories">Calories</label></h2>
                        <input type="text" id="edit-calories" name="meal_calories" pattern="^[0-9]+(\.[0-9]+)?$"
                            class="staff-input-primary staff-input-long" value="<?= $meal->calories ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-proteins">Proteins</label></h2>
                        <input type="text" id="edit-proteins" name="meal_proteins" pattern="^[0-9]+(\.[0-9]+)?$"
                            class="staff-input-primary staff-input-long" value="<?= $meal->proteins ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-fats">Fats</label></h2>
                        <input type="text" id="edit-fats" name="meal_fats" pattern="^[0-9]+(\.[0-9]+)?$"
                            class="staff-input-primary staff-input-long" value="<?= $meal->fats ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
