<?php

$id = $_GET['id'] ?? null;

session_start();

$sidebarActive = 2;

require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Delete Equipment",
    "showBack" => true,
    "goBackTo" => "/staff/eq/equipments/view/index.php?id=$id",
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../equipments.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard_with_role("eq", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="delete_equipment.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <input type="hidden" name="equipment_id" value="<?= $id ?>">
                <div class="staff-record-delete-div">
                    <h2>Are you sure you want to delete this equipment?</h2>
                    <p>This action cannot be undone.</p>
                    <button type="submit">Delete</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>