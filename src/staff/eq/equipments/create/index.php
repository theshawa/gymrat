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

// Prepare new Equipment object
$equipment = new Equipment();
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
                    
                    <!-- Equipment Name -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_name">Equipment Name</label></h2>
                        <input type="text" id="equipment_name" name="equipment_name"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->name ?? '') ?>">
                    </div>

                    <!-- Category -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_category">Category</label></h2>
                        <input type="text" id="equipment_category" name="equipment_category"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->category ?? '') ?>">
                    </div>

                    <!-- Quantity -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_quantity">Quantity</label></h2>
                        <input type="number" id="equipment_quantity" name="equipment_quantity"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->quantity ?? '') ?>" min="0">
                    </div>

                    <!-- Status -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_status">Status</label></h2>
                        <select name="equipment_status" id="equipment_status" class="staff-input-primary staff-input-long">
                            <option value="Available" <?= (isset($equipment->status) && $equipment->status == 'Available') ? 'selected' : '' ?>>Available</option>
                            <option value="In Use" <?= (isset($equipment->status) && $equipment->status == 'In Use') ? 'selected' : '' ?>>In Use</option>
                            <option value="Maintenance" <?= (isset($equipment->status) && $equipment->status == 'Maintenance') ? 'selected' : '' ?>>Maintenance</option>
                            <option value="Out of Order" <?= (isset($equipment->status) && $equipment->status == 'Out of Order') ? 'selected' : '' ?>>Out of Order</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_description">Description</label></h2>
                        <textarea id="equipment_description" name="equipment_description"
                            class="staff-textarea-primary staff-textarea-large"
                            placeholder="Enter equipment description"><?= htmlspecialchars($equipment->description ?? '') ?></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_image">Image</label></h2>
                        <input type="file" id="equipment_image" name="equipment_image" accept="image/*"
                            class="staff-input-primary staff-input-long">
                    </div>

                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
