<?php
session_start();

$id = $_GET['id'] ?? null;
$_SESSION['equipment_id'] = $id;

$sidebarActive = 2;

require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$equipment = new Equipment();
if (!isset($_SESSION['equipment'])) {
    try {
        $equipment->get_by_id($id);
        $_SESSION['equipment'] = serialize($equipment);
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch equipment: " . $e->getMessage(), "/staff/eq");
    }
} else {
    $equipment = unserialize($_SESSION['equipment']);
}

$menuBarConfig = [
    "title" => "Edit " . htmlspecialchars($equipment->name),
    "showBack" => true,
    "goBackTo" => "/staff/eq/equipments/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_equipment.php", "type" => "destructive"]
    ]
];

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
            <form action="edit_equipment.php" method="POST" enctype="multipart/form-data">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <input type="hidden" name="equipment_id" value="<?= $equipment->id ?>">

                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-title">Equipment Name</label></h2>
                        <input type="text" id="edit-title" name="equipment_name"
                            class="staff-input-primary staff-input-long"
                            value="<?= htmlspecialchars($equipment->name) ?>">
                    </div>

                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-category">Type</label></h2>
                        <input type="text" id="edit-category" name="equipment_category"
                            class="staff-input-primary staff-input-long"
                            value="<?= htmlspecialchars($equipment->type) ?>">
                    </div>

                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-quantity">Quantity</label></h2>
                        <input type="number" id="edit-quantity" name="equipment_quantity"
                            class="staff-input-primary staff-input-long"
                            value="<?= htmlspecialchars($equipment->quantity) ?>">
                    </div>

                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-status">Status</label></h2>
                        <select name="equipment_status" id="edit-status" class="staff-input-primary staff-input-long">
                            <option value="available" >Available</option>
                            <option value="not available" <?= $equipment->status == 'In Use' ? 'selected' : '' ?>>Not Available</option>
                        </select>
                    </div>

                    <div style="margin: 10px 0">
                        <h2><label for="edit-description">Description</label></h2>
                        <textarea id="edit-description" name="equipment_description"
                            class="staff-textarea-primary staff-textarea-large"
                            placeholder="Enter equipment description"><?= htmlspecialchars($equipment->description) ?></textarea>
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
