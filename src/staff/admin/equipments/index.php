<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$pageTitle = "Manage Equipment Logs";
$sidebarActive = 8;

require_once "../../../db/models/LogRecord.php";
require_once "../../../alerts/functions.php";

$logRecords = [];
$logRecordModel = new LogRecord();
try {
    $logRecords = $logRecordModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch log records: " . $e->getMessage(), "/staff/admin");
}

$menuBarConfig = [
    "title" => "Equipment Log Records"
];

$infoCardConfig = [
    "defaultName" => "Log Record",
    "useListView" => true,
    "showDescription" => false,
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/admin/equipments/view/index.php",
    "cards" => $logRecords
];

require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>