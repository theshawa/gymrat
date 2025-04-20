<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");


$setFilter = $_GET['filter'] ?? 0;
$pageTitle = "Manage Rats";
$sidebarActive = 3;

require_once "../../../db/models/Customer.php";
require_once "../../../alerts/functions.php";

$customerModel = new Customer();
try {
    $customers = $customerModel->get_all($setFilter);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin");
    exit;
}

$hasUnassignedRats = false;
$customerModel = new Customer();
try {
    $hasUnassignedRats = $customerModel->has_trainer_unassigned();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access unassigned rats: " . $e->getMessage();
}

$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ($setFilter == 1) ? 
        ["title" => "Show All Rats", "href" => "/staff/admin/rats/index.php", "type" => "primary"] :
        ["title" => "Show Unassigned Rats", "href" => "/staff/admin/rats/index.php?filter=1", 
        "type" => "primary", "setAttentionDot" => $hasUnassignedRats]
    ]
];


$infoCardConfig = [
    "showImage" => true,
    "useAvatar" => true,
    "concatName" => true,
    "showExtend" => true,
    "extendTo" => "/staff/admin/rats/view/index.php",
    "cards" => $customers,
    "showCreatedAt" => false,
    "gridColumns" => 1
];



require_once "../pageconfig.php";
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