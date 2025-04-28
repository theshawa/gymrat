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

$equipmentModel = new Equipment();
$equipments = $equipmentModel->get_all();

?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="create_record.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">

                    <!-- Equipment List -->
                    <div style="margin-bottom: 10px;">
                        <h2>Equipment List</h2>
                        <?php foreach ($equipments as $equipment): ?>
                            <div style="margin-bottom: 10px;">
                                <h3><?= htmlspecialchars($equipment->name) ?></h3>
                                <input type="hidden" name="equipment_ids[]" value="<?= htmlspecialchars($equipment->id) ?>">
                                <label for="status_<?= $equipment->id ?>">Status:</label>
                                <select name="statuses[]" id="status_<?= $equipment->id ?>" class="staff-input-primary">
                                    <option value="Good">Good</option>
                                    <option value="Bad">Bad</option>
                                    <option value="Broken">Broken</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
