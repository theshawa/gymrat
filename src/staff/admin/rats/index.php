<?php

$pageTitle = "Manage Rats";
$sidebarActive = 3;
$menuBarConfig = [
    "title" => $pageTitle,
];

require_once "../../../db/models/Customer.php";

$customerModel = new Customer();
try {
    $customers = $customerModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin");
    exit;
}


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

require_once "../../../alerts/functions.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

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