<?php
session_start();

$sidebarActive = 3;

require_once "../../../../db/models/LogRecord.php";
require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Create Log Record",
    "showBack" => true,
    "goBackTo" => "/staff/eq/log-records/index.php",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_log_record.php", "type" => "destructive"]
    ]
];

// Prepare new LogRecord object
$logRecord = new LogRecord();
if (isset($_SESSION['log_record'])) {
    $logRecord = unserialize($_SESSION['log_record']);
} else {
    $logRecord->fill([]);
    $_SESSION['log_record'] = serialize($logRecord);
}

require_once "../../pageconfig.php";
$pageConfig['styles'][] = "../log-record.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");

$equipmentName = '';
$equipment_id = $_GET['id'] ?? null;

if (isset($equipment_id)) {
    $equipmentModel = new Equipment();
    $equipmentModel->get_by_id($equipment_id);
    $equipmentName = $equipmentModel->name ?? 'Unknown';
}
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="create_log_record.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <input type="hidden" name="equipment_id" value="<?= htmlspecialchars($equipment_id) ?>">

                    <!-- Equipment Name -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="equipment_name">Equipment Name</label></h2>
                        <input type="text" id="equipment_name" name="equipment_name"
                            class="staff-input-primary staff-input-long" value="<?= htmlspecialchars($equipmentName) ?>" readonly>
                    </div>

                    <!-- Status -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="log_status">Status</label></h2>
                        <select name="status" id="log_status" class="staff-input-primary staff-input-long">
                            <option value="Completed" <?= (isset($logRecord->status) && $logRecord->status == 'Completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="Pending" <?= (isset($logRecord->status) && $logRecord->status == 'Pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div style="margin-bottom: 10px;">
                        <h2><label for="log_description">Description</label></h2>
                        <textarea id="log_description" name="description"
                            class="staff-textarea-primary staff-textarea-large"
                            placeholder="Enter log record description"><?= htmlspecialchars($logRecord->description ?? '') ?></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
