<?php
session_start();

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$id = $_GET['id'] ?? null;
$sidebarActive = 4;

require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Delete Meal",
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meals/view/index.php?id=$id",
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meals.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="delete_meal.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <input type="hidden" name="meal_id" value="<?= $id ?>">
                <div class="staff-record-delete-div">
                    <h2>Are you sure you want to delete this meal?</h2>
                    <p>This action cannot be undone.</p>
                    <button type="submit">Delete</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
