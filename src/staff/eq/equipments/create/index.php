<?php

session_start();

$sidebarActive = 2;

require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Create Equipment",
    "showBack" => true,
    "goBackTo" => "/staff/eq/equipments/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_equipment.php", "type" => "destructive"]
    ]
];

$equipment=new Equipment();
if (isset($_SESSION['equipment'])) {
    $equipment = unserialize($_SESSION['equipment']);
} else {
    $equipment->fill([]);
    $_SESSION['equipment'] = serialize($equipment);
}

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../equipment.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="create_equipment.php" method="POST" enctype="multipart/form-data">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-title">Equipment Name</label></h2>
                        <input type="text" id="edit-title" name="equipment_name"
                            class="staff-input-primary staff-input-long" value="<?= $equipment->name ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-category">Category</label></h2>
                        <input type="text" id="edit-category" name="equipment_category"
                            class="staff-input-primary staff-input-long" value="<?= $equipment->category ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-quantity">Quantity</label></h2>
                        <input type="number" id="edit-quantity" name="equipment_quantity"
                            class="staff-input-primary staff-input-long" value="<?= $equipment->quantity ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-status">Status</label></h2>
                        <select name="equipment_status" id="edit-status" class="staff-input-primary staff-input-long">
                            <option value = "Available" <?= $equipment->status == 'Available' ? 'selected' : '' ?>>Available</option>
                            <option value = "In Use" <?= $equipment->status == 'In Use' ? 'selected' : '' ?>>In Use</option>
                            <option value = "Maintenance" <?= $equipment->status == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value = "Out of Order" <?= $equipment->status == 'Out of Order' ? 'selected' : '' ?>>Out of Order</option>
                        </select>
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="edit-description">Description</label></h2>
                        <textarea id="edit-description" name="equipment_description"
                            class="staff-textarea-primary staff-textarea-large"
                            placeholder="Enter equipment description"><?= $equipment->description ?></textarea>
                    </div>
                    <div style="margin: 10px 0">
                        <h2><label for="edit-image">Image</label></h2>
                        <input type="file" id="edit-image" name="equipment_image" accept="image/*"
                            class="staff-input-primary staff-input-long">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
