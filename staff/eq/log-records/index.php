<?php

$pageTitle = "Equipment Log Records";
$sidebarActive = 3;

// Menu bar configuration
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => false,
    "options" => []
];

// Simulated log data (replace with database queries in production)
$logRecords = [
    [
        "id" => 1,
        "equipment" => "Leg Press Machine",
        "action" => "Maintenance Completed",
        "date" => "2024-10-10",
        "status" => "Completed"
    ],
    [
        "id" => 2,
        "equipment" => "Squat Rack",
        "action" => "Scheduled Maintenance",
        "date" => "2024-11-15",
        "status" => "Pending"
    ],
    [
        "id" => 3,
        "equipment" => "Bench Press",
        "action" => "Repaired",
        "date" => "2024-09-20",
        "status" => "Completed"
    ],
    [
        "id" => 4,
        "equipment" => "Dumbbells",
        "action" => "Usage Logged",
        "date" => "2024-11-18",
        "status" => "Completed"
    ],
    [
        "id" => 5,
        "equipment" => "Lat Pulldown Machine",
        "action" => "Replaced Cable",
        "date" => "2024-10-25",
        "status" => "Completed"
    ],
    [
        "id" => 6,
        "equipment" => "Chest Fly Machine",
        "action" => "Scheduled Maintenance",
        "date" => "2024-12-01",
        "status" => "Pending"
    ],
    [
        "id" => 7,
        "equipment" => "Treadmill",
        "action" => "Lubrication",
        "date" => "2024-10-05",
        "status" => "Completed"
    ],
    [
        "id" => 8,
        "equipment" => "Rowing Machine",
        "action" => "Replaced Handle",
        "date" => "2024-11-10",
        "status" => "Completed"
    ],
    [
        "id" => 9,
        "equipment" => "Elliptical",
        "action" => "Scheduled Maintenance",
        "date" => "2024-11-25",
        "status" => "Pending"
    ],
    [
        "id" => 10,
        "equipment" => "Calf Raise Machine",
        "action" => "Repaired Foot Pedal",
        "date" => "2024-10-30",
        "status" => "Completed"
    ]
];


require_once "../pageconfig.php";

$pageConfig['styles'][] = "./log-records.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        
        <!-- Log Records Table -->
        <div class="log-table-container">
            <!-- <h1>Equipment Log Records </h1> -->
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
                            <td><?php echo htmlspecialchars($log['id']); ?></td>
                            <td><?php echo htmlspecialchars($log['equipment']); ?></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($log['date']); ?></td>
                            <td class="<?php echo htmlspecialchars(strtolower($log['status'])); ?>">
                                <?php echo htmlspecialchars($log['status']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
    /* General body styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Main container */
.staff-base-container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Table container */
.log-table-container {
    margin-top: 20px;
}

.log-table-container h1 {
    font-size: 1.8rem;
    color: #444;
    margin-bottom: 20px;
    text-align: center;
}

/* Table styling */
.log-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.log-table thead th {
    background-color: #f8f9fa;
    color: #333;
    font-weight: bold;
    padding: 10px;
    border-bottom: 2px solid #ddd;
    text-align: left;
}

.log-table tbody tr td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

/* Status column styling */
.log-table tbody tr td.completed {
    color: #28a745; /* Green for Completed */
    font-weight: bold;
}

.log-table tbody tr td.pending {
    color: #ffc107; /* Yellow for Pending */
    font-weight: bold;
}

/* Responsive design */
@media (max-width: 768px) {
    .log-table {
        font-size: 0.9rem;
    }
}

</style>

<?php require_once "../../includes/footer.php"; ?>
