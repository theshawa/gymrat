<?php
session_start();

require_once "../../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? 0;

$pageStyles = ["../../finance.css"];
$sidebarActive = 7;


require_once "../../../../../db/models/Customer.php";
require_once "../../../../../db/models/MembershipPlan.php";
require_once "../../../../../db/models/MembershipPayment.php";
require_once "../../../../../alerts/functions.php";


$customer = new Customer();
$current_payment = new MembershipPayment();
$current_plan = new MembershipPlan();

try {
    $current_payment->id = $id;
    $current_payment->get_by_id();
    $customer->id = $current_payment->customer;
    $customer->get_by_id();
    $current_plan->id = $current_payment->membership_plan;
    $current_plan->get_by_id();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch data: " . $e->getMessage(), "/staff/admin/finance/sales");
    exit;
}

$menuBarConfig = [
    "title" => "Payment No. " . $current_payment->id,
    "showBack" => true,
    "goBackTo" => "/staff/admin/finance/sales/index.php",
];



require_once "../../../pageconfig.php";
require_once "../../../../includes/header.php";
require_once "../../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../../includes/menubar.php"; ?>
        <div style="margin: 20px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px;">

            <!-- Deafult Right Layout -->
            <div class="rat-view-profile">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left; padding: 15px;">
                <?php if (!empty($customer->avatar)): ?>
                    <img src="../../../../../uploads/<?= $customer->avatar ?>" alt="Customer Avatar"  class="rat-view-avatar">
                <?php else: ?>
                    <img src="../../../../../uploads/default-images/infoCardDefault.png" alt="Default Avatar" class="rat-view-avatar">
                <?php endif; ?>
                </div>
                <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: start; text-align: left;">
                    <h1 style="margin: 10px; font-size: 28px;"><?= $customer->fname . " " . $customer->lname ?></h1>
                    <h1 style="margin: 10px;"><?= $customer->email ?></h1>
                </div>
            </div>

            <!-- default left layout -->
            <div style="margin: 0 10px;">
                <div style="margin: 10px 0;">
                    <h1 style="margin-bottom: 5px">Membership Plan</h1>
                    <p><?= $current_plan->name ?></p>
                </div>
                <div style="margin: 10px 0;">
                    <h1 style="margin-bottom: 5px">Description</h1>
                    <p><?= $current_plan->description ?></p>
                </div>
                <div style="margin: 10px 0;">
                    <h1 style="margin-bottom: 5px">Amount</h1>
                    <p>Rs. <?= number_format($current_payment->amount, 2) ?></p>
                </div>
                <div style="margin: 10px 0;">
                    <h1 style="margin-bottom: 5px">Completed At</h1>
                    <p><?= $current_payment->completed_at ? $current_payment->completed_at->format('Y-m-d') : "Incomplete" ?></p>
                </div>
                <?php if ($current_payment->completed_at): ?>
                    <div style="margin: 10px 0;">
                        <h1 style="margin-bottom: 5px">Expiry Date</h1>
                        <p><?= $current_payment->completed_at->modify("+{$current_plan->duration} days")->format('Y-m-d') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../../includes/footer.php"; ?>
