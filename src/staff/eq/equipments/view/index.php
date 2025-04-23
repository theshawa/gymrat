<?php
session_start();

$id = $_GET['id'] ?? null;

require_once "../../../../db/models/Equipment.php";
require_once "../../../../alerts/functions.php";

$equipment = new Equipment();
try {
    $equipment->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch equipment: " . $e->getMessage(), "/staff/eq/equipments");
}
$_SESSION['equipment'] = serialize($equipment);

$sidebarActive = 2;

$menuBarConfig = [
    "title" => htmlspecialchars($equipment->name),
    "showBack" => true,
    "goBackTo" => "/staff/eq/equipments/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Equipment", "href" => "/staff/eq/equipments/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Equipment", "href" => "/staff/eq/equipments/delete/index.php?id=$id", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";
$pageConfig['styles'][] = "../equipmentView.css";
$pageConfig['styles'][] = "../equipment.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>

        <div class="equipment-view-container">
            <div>
                <h2 style="margin-bottom: 20px;">
                    Equipment Details
                </h2>

                <div class="equipment-view-details">
                    <p><strong>Name:</strong></p>
                    <p class="alt"><?= htmlspecialchars($equipment->name) ?></p>
                </div>
                <hr>

                <div class="equipment-view-details">
                    <p><strong>Category:</strong></p>
                    <p class="alt"><?= htmlspecialchars($equipment->category??'N/A') ?></p>
                </div>
                <hr>

                <div class="equipment-view-details">
                    <p><strong>Quantity:</strong></p>
                    <p class="alt"><?= htmlspecialchars($equipment->quantity??'N/A') ?></p>
                </div>
                <hr>

                <div class="equipment-view-details">
                    <p><strong>Status:</strong></p>
                    <p class="alt"><?= htmlspecialchars($equipment->status??'N/A') ?></p>
                </div>
                <hr>

                <div class="equipment-view-details">
                    <p><strong>Description:</strong></p>
                    <p class="alt"><?= nl2br(htmlspecialchars($equipment->description??'N/A')) ?></p>
                </div>
            </div>

            <div>
                <div style="margin: 10px;" class="img">
                    <h2 style="margin-bottom: 20px;">
                        Product Image
                    </h2>
                    <?php if ($equipment->image): ?>
                        <img src="/uploads/<?= htmlspecialchars($equipment->image) ?>" alt="<?= htmlspecialchars($equipment->name) ?>" style="width: 300px; height: 300px; object-fit: cover;">
                    <?php else: ?>
                        <img src="/staff/eq/equipments/view/default-image.jpg" alt="Default Image" style="width: 300px; height: 300px; object-fit: cover;">
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
