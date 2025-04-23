<?php
session_start();

$pageTitle = "Equipment Log Records";
$sidebarActive = 3;

require_once "../pageconfig.php";
$pageConfig['styles'][] = "../log-records/log-records.css";

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
    ["id" => 1, "created_at" => "2024-10-10", "description" => "Maintenance completed for Leg Press Machine"],
    ["id" => 2, "created_at" => "2024-11-15", "description" => "Scheduled maintenance for Squat Rack"],
    ["id" => 3, "created_at" => "2024-09-20", "description" => "Bench Press repaired successfully"],
    ["id" => 4, "created_at" => "2024-11-18", "description" => "Dumbbells usage logged"],
    ["id" => 5, "created_at" => "2024-10-25", "description" => "Cable replaced for Lat Pulldown Machine"],
    ["id" => 6, "created_at" => "2024-12-01", "description" => "Scheduled maintenance for Chest Fly Machine"],
    ["id" => 7, "created_at" => "2024-10-05", "description" => "Treadmill lubrication completed"],
    ["id" => 8, "created_at" => "2024-11-10", "description" => "Handle replaced for Rowing Machine"],
    ["id" => 9, "created_at" => "2024-11-25", "description" => "Scheduled maintenance for Elliptical"],
    ["id" => 10, "created_at" => "2024-10-30", "description" => "Calf Raise Machine foot pedal repaired"],
    ["id" => 11, "created_at" => "2024-09-15", "description" => "Smith Machine inspected"],
    ["id" => 12, "created_at" => "2024-10-05", "description" => "Cable adjustment done for Seated Row Machine"],
    ["id" => 13, "created_at" => "2024-12-15", "description" => "Leg Curl Machine scheduled for maintenance"],
    ["id" => 14, "created_at" => "2024-10-20", "description" => "Seat cushion replaced for Leg Extension Machine"],
    ["id" => 15, "created_at" => "2024-11-01", "description" => "Hack Squat Machine joints lubricated"],
    ["id" => 16, "created_at" => "2024-09-28", "description" => "Pull-up Bar grips replaced"],
    ["id" => 17, "created_at" => "2024-10-12", "description" => "Pec Deck Machine maintenance completed"],
    ["id" => 18, "created_at" => "2024-11-08", "description" => "Cable tightened for Cable Crossover Machine"],
    ["id" => 19, "created_at" => "2024-09-18", "description" => "Frame inspected for Incline Bench Press"],
    ["id" => 20, "created_at" => "2024-12-05", "description" => "Stepper Machine motor serviced"],
];
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>

        <div class="log-records-card">
            <h1 class="log-title">Equipment Log Records</h1>

            <div class="log-table-wrapper">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created At</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logRecords as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['id']) ?></td>
                                <td><?= htmlspecialchars($log['created_at']) ?></td>
                                <td><?= htmlspecialchars($log['description']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>
