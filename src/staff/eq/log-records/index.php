<?php
session_start();

$pageTitle = "Equipment Log Records";
$sidebarActive = 3;

require_once "../pageconfig.php";
$pageConfig['styles'][] = "./log-records.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
require_once "../../../alerts/functions.php";
require_once "../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");

// Menu bar configuration
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => false,
    "options" => []
];

// Simulated log data
$logRecords = [
    ["id" => 1, "equipment" => "Leg Press Machine", "action" => "Maintenance Completed", "date" => "2024-10-10", "status" => "Completed"],
    ["id" => 2, "equipment" => "Squat Rack", "action" => "Scheduled Maintenance", "date" => "2024-11-15", "status" => "Pending"],
    ["id" => 3, "equipment" => "Bench Press", "action" => "Repaired", "date" => "2024-09-20", "status" => "Completed"],
    ["id" => 4, "equipment" => "Dumbbells", "action" => "Usage Logged", "date" => "2024-11-18", "status" => "Completed"],
    ["id" => 5, "equipment" => "Lat Pulldown Machine", "action" => "Replaced Cable", "date" => "2024-10-25", "status" => "Completed"],
    ["id" => 6, "equipment" => "Chest Fly Machine", "action" => "Scheduled Maintenance", "date" => "2024-12-01", "status" => "Pending"],
    ["id" => 7, "equipment" => "Treadmill", "action" => "Lubrication", "date" => "2024-10-05", "status" => "Completed"],
    ["id" => 8, "equipment" => "Rowing Machine", "action" => "Replaced Handle", "date" => "2024-11-10", "status" => "Completed"],
    ["id" => 9, "equipment" => "Elliptical", "action" => "Scheduled Maintenance", "date" => "2024-11-25", "status" => "Pending"],
    ["id" => 10, "equipment" => "Calf Raise Machine", "action" => "Repaired Foot Pedal", "date" => "2024-10-30", "status" => "Completed"],
    ["id" => 11, "equipment" => "Smith Machine", "action" => "Inspected", "date" => "2024-09-15", "status" => "Completed"],
    ["id" => 12, "equipment" => "Seated Row Machine", "action" => "Cable Adjustment", "date" => "2024-10-05", "status" => "Completed"],
    ["id" => 13, "equipment" => "Leg Curl Machine", "action" => "Scheduled Maintenance", "date" => "2024-12-15", "status" => "Pending"],
    ["id" => 14, "equipment" => "Leg Extension Machine", "action" => "Seat Cushion Replacement", "date" => "2024-10-20", "status" => "Completed"],
    ["id" => 15, "equipment" => "Hack Squat Machine", "action" => "Lubricated Joints", "date" => "2024-11-01", "status" => "Completed"],
    ["id" => 16, "equipment" => "Pull-up Bar", "action" => "Grip Replaced", "date" => "2024-09-28", "status" => "Completed"],
    ["id" => 17, "equipment" => "Pec Deck Machine", "action" => "Maintenance Completed", "date" => "2024-10-12", "status" => "Completed"],
    ["id" => 18, "equipment" => "Cable Crossover Machine", "action" => "Cable Tightened", "date" => "2024-11-08", "status" => "Completed"],
    ["id" => 19, "equipment" => "Incline Bench Press", "action" => "Frame Inspected", "date" => "2024-09-18", "status" => "Completed"],
    ["id" => 20, "equipment" => "Stepper Machine", "action" => "Motor Serviced", "date" => "2024-12-05", "status" => "Pending"],
];
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>

        <div class="log-table-container">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Equipment</th>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logRecords as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['id']) ?></td>
                            <td><?= htmlspecialchars($log['equipment']) ?></td>
                            <td><?= htmlspecialchars($log['action']) ?></td>
                            <td><?= htmlspecialchars($log['date']) ?></td>
                            <td class="<?= strtolower(htmlspecialchars($log['status'])) ?>">
                                <?= htmlspecialchars($log['status']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>
