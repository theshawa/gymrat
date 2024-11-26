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

$equipment = new Equipment();
if (isset($_SESSION['equipment'])){
    $equipment = $_SESSION['equipment'];
} else {
    $equipment->fill([]);
//    $_SESSION['equipment'] = $equipment;
}

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
            <form action="create_equipment.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-title">Title</label></h2>
                        <input type="text" id="edit-title" name="equipment_name"
                               class="staff-input-primary staff-input-long" value="<?= $equipment->name ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-type">Type</label></h2>
                        <input type="text" id="edit-type" name="equipment_type"
                               class="staff-input-primary staff-input-long" value="<?= $equipment->type ?>">
                    </div>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-manufacturer">Manufacturer</label></h2>
                        <input type="text" id="edit-manufacturer" name="equipment_manufacturer"
                               class="staff-input-primary staff-input-long" value="<?= $equipment->manufacturer ?>">
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-description">Description</label></h2>
                        <textarea id="edit-description" name="equipment_description"
                                  class="staff-textarea-primary staff-textarea-large"
                                  placeholder="Enter a equipment description"><?= $equipment->description ?></textarea>
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-purchase-date">Purchase Date</label></h2>
                        <input type="datetime-local" id="edit-purchase-date" name="equipment_purchase_date"
                               class="staff-input-primary staff-input-long"
                               value="<?= isset($equipment->purchase_date) ? $equipment->purchase_date->format('Y-m-d\TH:i') : '' ?>">
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-last-maintenance">Last Maintenance Date</label></h2>
                        <input type="datetime-local" id="edit-last-maintenance" name="equipment_last_maintenance"
                               class="staff-input-primary staff-input-long"
                               value="<?= isset($equipment->last_maintenance) ? $equipment->last_maintenance->format('Y-m-d\TH:i') : '' ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>