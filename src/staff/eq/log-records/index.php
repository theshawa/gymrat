<?php
session_start();

require_once "../../../alerts/functions.php";
require_once "../../../db/models/LogRecord.php";
require_once "../../../db/models/Equipment.php";

$sidebarActive = 3;

require_once "../pageconfig.php";
$pageConfig['styles'][] = "../equipments/equipment.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");

// Fetch all log records
$logRecordModel = new LogRecord();
$logRecords = $logRecordModel->get_all();

$equipmentModel = new Equipment();
?>

<main>
    <div class="staff-base-container">
        <div class="staff-page-header">
            <h1>Manage Log Records</h1>
            <a href="./create/index.php" class="staff-button-small">Create New Log Record</a>
        </div>

        <div class="staff-records-container">
            <?php if (empty($logRecords)): ?>
                <p>No log records available.</p>
            <?php else: ?>
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>Equipment Details</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logRecords as $logRecord): ?>
                            <tr>
                                <td><?= htmlspecialchars($logRecord->id) ?></td>
                                <td>
                                    <?php 
                                    $equipmentDetails = json_decode($logRecord->description, true);
                                    if (is_array($equipmentDetails)): 
                                    ?>
                                        <ul>
                                            <?php foreach ($equipmentDetails as $detail): ?>
                                                <?php
                                                    $equipmentModel->get_by_id($detail['equipment_id']);
                                                    $equipmentName = $equipmentModel->name ?? 'Unknown';
                                                ?>
                                                <li>
                                                    <?= htmlspecialchars($equipmentName) ?> - <?= htmlspecialchars($detail['status']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>Invalid data</p>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($logRecord->created_at->format('Y-m-d')) ?></td>
                                <td>
                                    <a href="./edit/index.php?id=<?= $logRecord->id ?>" class="staff-button-small">Edit</a>
                                    <a href="./delete/index.php?id=<?= $logRecord->id ?>" class="staff-button-small staff-button-destructive">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>
