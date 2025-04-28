<?php
session_start();

require_once "../../../auth-guards.php";
auth_required_guard("eq", "/staff/login");

$pageTitle = "Equipments";
$sidebarActive = 2;

require_once "../../../db/models/Equipment.php";
require_once "../../../alerts/functions.php";

$equipmentModel = new Equipment();
$equipments = [];
try {
    $equipments = $equipmentModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch equipments: " . $e->getMessage(), "/staff/eq");
    exit;
}

$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        [
            "title" => "Create Equipment",
            "href" => "./create/index.php",
            "type" => "secondary"
        ]
    ]
];

$infoCardConfig = [
    "showImage" => false,
    "showExtend" => true,
    "extendTo" => "./view/index.php",
    "cards" => $equipments,
    "showCreatedAt" => false
];

require_once "../pageconfig.php";

$pageConfig['styles'][] = "./equipments.css";

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
