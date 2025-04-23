<?php
session_start();

require_once "../../../alerts/functions.php";
require_once "../../../db/models/Equipment.php";

$sidebarActive = 2;

require_once "../pageconfig.php";
$pageConfig['styles'][] = "../equipments/equipment.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");

// Fetch all equipment records
$equipmentModel = new Equipment();
$equipments = $equipmentModel->get_all();
?>

<main>
    <div class="staff-base-container">
        <div class="staff-page-header">
            <h1>Manage Equipments</h1>
            <a href="./create/index.php" class="staff-button-primary">Create New Equipment</a>
        </div>

        <div class="staff-records-container">
            <?php if (empty($equipments)): ?>
                <p>No equipments available.</p>
            <?php else: ?>
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipments as $equipment): ?>
                            <tr>
                                <td><?= htmlspecialchars($equipment->name) ?></td>
                                <td><?= htmlspecialchars($equipment->category ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($equipment->quantity ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($equipment->status ?? 'N/A') ?></td>
                                <td>
                                    <a href="./view/index.php?id=<?= $equipment->id ?>" class="staff-button-small">View</a>
                                    <a href="./edit/index.php?id=<?= $equipment->id ?>" class="staff-button-small">Edit</a>
                                    <a href="./delete/index.php?id=<?= $equipment->id ?>" class="staff-button-small staff-button-destructive">Delete</a>
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
