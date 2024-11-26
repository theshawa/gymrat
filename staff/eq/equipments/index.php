<?php

$pageTitle = "Manage Equipments";
$sidebarActive = 2;

$menuBarConfig = [
    "title" => "Manage Equipments",
    "useLink" => true,
    "options" => [
        ["title" => "New Equipment", "href" => "/staff/eq/equipments/new/index.php", "type" => "secondary"],
        // ["title" => "Delete Equipment", "href" => "/staff/eq/equipments/delete/index.php?id=$id", "type" => "destructive"]
    ]
];


require_once "../../../db/models/Equipment.php";

require_once "../../../alerts/functions.php";

// All equipment items in a single list
$equipmentModel = new Equipment();
try {
    $equipmentList = $equipmentModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch equipment: " . $e->getMessage(), "/staff/wnmp");
}


$infoCardConfig = [
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/eq/equipments/view/index.php",
    "cards" => $equipmentList,
];

require_once "../pageconfig.php";
$pageConfig['styles'][] = "./equipment.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";


require_once "../../../auth-guards.php";
auth_required_guard_with_role("eq", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
<!--        <h1>Manage Equipments | Equipment Manager</h1>-->
<!--        <div class="equipment-list mt-4">-->
<!--            <ul>-->
<!--                --><?php //foreach ($equipmentList as $equipment): ?>
<!--                    <li class="equipment-item">-->
<!--                        <a href="/staff/eq/equipments/view/index.php?id=--><?php //echo htmlspecialchars($equipment['id']); ?><!--">-->
<!--                            <strong>--><?php //echo htmlspecialchars($equipment['name']); ?><!--</strong>-->
<!--                        </a>-->
<!--                        <p class="description">--><?php //echo htmlspecialchars($equipment['description']); ?><!--</p>-->
<!--                    </li>-->
<!--                    <hr />-->
<!--                --><?php //endforeach; ?>
<!--            </ul>-->
<!--        </div>-->
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>
