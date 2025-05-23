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

                    <!-- Type -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_category">Type</label></h2>
                        <input type="text" id="equipment_category" name="equipment_category"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->type ?? '') ?>">
                    </div>

                    <!-- Quantity -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_quantity">Quantity</label></h2>
                        <input type="number" id="equipment_quantity" name="equipment_quantity"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->quantity ?? 0) ?>" min="0">
                    </div>

                    <!-- Status -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_status">Status</label></h2>
                        <select name="equipment_status" id="equipment_status" class="staff-input-primary staff-input-long">
                            <option value="available" <?= (isset($equipment->status) && $equipment->status == 'available') ? 'selected' : '' ?>>Available</option>
                            <option value="not available" <?= (isset($equipment->status) && $equipment->status == 'not available') ? 'selected' : '' ?>>Not Available</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_description">Description</label></h2>
                        <textarea id="equipment_description" name="equipment_description"
                            class="staff-textarea-primary staff-textarea-large"
                            placeholder="Enter equipment description"><?= htmlspecialchars($equipment->description ?? '') ?></textarea>
                    </div>

                    <!-- Manufacturer -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_manufacturer">Manufacturer</label></h2>
                        <input type="text" id="equipment_manufacturer" name="equipment_manufacturer"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->manufacturer ?? '') ?>">
                    </div>

                    <!-- Purchase Date -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_purchase_date">Purchase Date</label></h2>
                        <input type="date" id="equipment_purchase_date" name="equipment_purchase_date"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipment->purchase_date->format('Y-m-d') ?? '') ?>">
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
