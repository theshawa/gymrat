<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$pageTitle = "Customers Sales";
$sidebarActive = 7;

require_once "../../../../db/models/Customer.php";
require_once "../../../../alerts/functions.php";

$customerModel = new Customer();
try {
    $customers = $customerModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch customers: " . $e->getMessage(), "/staff/admin/finance");
    exit;
}

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/finance"
];

$infoCardConfig = [
    "showImage" => true,
    "useAvatar" => true,
    "concatName" => true,
    "showExtend" => true,
    "extendTo" => "/staff/admin/finance/customers/view/index.php",
    "cards" => $customers,
    "showCreatedAt" => false,
    "gridColumns" => 1
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
