<?php
$id = $_GET['id'] ?? null;

// Simulated equipment data (this should be fetched from a database)
$equipment = [
    "id" => 001,
    "name" => "Leg Press Machine",
    "type" => "Legs",
    "last_maintenance" => "2024-10-10",
    "purchase_date" => "2022-05-15",
    "manufacturer" => "FitnessPro Inc.",
    "description" => "A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.A versatile machine designed to target quadriceps, hamstrings, and glutes effectively",
    "img" => null
];

$sidebarActive = 2;

$menuBarConfig = [
    "title" => $equipment['name'],
    "showBack" => true,
    "goBackTo" => "/staff/eq/equipments/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Equipment", "href" => "/staff/eq/equipments/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Equipment", "href" => "/staff/eq/equipments/delete/index.php?id=$id", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
$pageConfig['styles'][] = "equipmentView.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="base-container">

    <?php require_once "../../../includes/menubar.php"; ?>

        <!-- Equipment details container -->
        <div class="view-equipment-container">

            <div>
                <!-- Equipment Image -->
                <?php if ($equipment['img']): ?>
                    <img src="<?= htmlspecialchars($equipment['img']) ?>" alt="<?= htmlspecialchars($equipment['name']) ?>" style="width: 100%; max-width: 400px; margin-bottom: 20px;">
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>

                <!-- Equipment Details -->
                 <div class="equipment">
                     <h2 style="margin-bottom: 20px;">Equipment Details</h2>
                     <ul>
                         <li><strong>ID:</strong> <?= htmlspecialchars($equipment['id']) ?></li>
                         <li><strong>Name:</strong> <?= htmlspecialchars($equipment['name']) ?></li>
                         <li><strong>Type:</strong> <?= htmlspecialchars($equipment['type']) ?></li>
                         <li><strong>Last Maintenance:</strong> <?= htmlspecialchars($equipment['last_maintenance']) ?></li>
                         <li><strong>Purchase Date:</strong> <?= htmlspecialchars($equipment['purchase_date']) ?></li>
                         <li><strong>Manufacturer:</strong> <?= htmlspecialchars($equipment['manufacturer']) ?></li>
                     </ul>

                 </div>
            </div>

            <!-- Equipment Description -->
            <div class="description">
                <h2 style="margin-bottom: 20px;">Description</h2>
                <p><?= htmlspecialchars($equipment['description']) ?></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
