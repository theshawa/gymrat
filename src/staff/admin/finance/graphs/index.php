<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MembershipPayment.php";
require_once "../../../../db/models/MembershipPlan.php";

$pageTitle = "Graphs";
$pageStyles = ["../finance.css"];
$sidebarActive = 7;


$sales = null;
$membership_titles = null;
$membershipPaymentModel = new MembershipPayment();
$membershipPlanModel = new MembershipPlan();
try {
    $sales = $membershipPaymentModel->get_all();
    $membership_titles = $membershipPlanModel->get_all_titles();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch sales details: " . $e->getMessage(), "/staff/admin/finance");
    exit;
}

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/finance",
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
