<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../db/models/LogRecord.php";
require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Equipment.php";

$logRecordId = $_GET['id'] ?? null;
if (!$logRecordId) {
    redirect_with_error_alert("Log record ID is required.", "/staff/admin/equipments/index.php");
}

$logRecord = new LogRecord();
try {
    $logRecord->get_by_id($logRecordId);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch log record: " . $e->getMessage(), "/staff/admin/equipments/index.php");
}

// Fetch all equipment names and map them by ID
$equipmentModel = new Equipment();
$equipments = $equipmentModel->get_all();
$equipmentMap = [];
foreach ($equipments as $equipment) {
    $equipmentMap[$equipment->id] = $equipment->name;
}

// Replace equipment IDs with names in the description
$description = json_decode($logRecord->description, true);
foreach ($description as &$item) {
    $item['equipment_name'] = $equipmentMap[$item['equipment_id']] ?? 'Unknown';
}
unset($item);

$pageTitle = "View Log Record #" . $logRecord->id;
$sidebarActive = 8;

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/equipments/index.php",
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="log-record-details">
            <h1>
                ID
            </h1>
            <p style="margin: 10px 0; margin-top: 5px;">
                <?= $logRecord->id; ?>
            </p>
            <h1>
                Equipment Manager
            </h1>
            <p style="margin: 10px 0; margin-top: 5px;">
                <?= $logRecord->equipment_manager; ?>
            </p>

            <h1>
                Created At
            </h1>
            <p style="margin: 10px 0; margin-top: 5px;">
                <?= $logRecord->created_at->format('Y-m-d H:i:s'); ?>
            </p>
            <h1 style="margin-bottom: 5px;">Description:</h1>
            <?php 
                foreach ($description as $item): ?>                    
                    <div style="display: flex; flex-direction: row; padding: 5px 0; align-items: center;">
                    <h3><?= $item['equipment_name']; ?> :</h3>
                    <p>&emsp;<?= $item['status']; ?></p>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>