<?php
session_start();

require_once "../../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$id = $_GET['id'] ?? null;
$payment_id = $_GET['payment'] ?? 0;

$pageStyles = ["../../finance.css"];
$sidebarActive = 7;


require_once "../../../../../db/models/Customer.php";
require_once "../../../../../db/models/MembershipPlan.php";
require_once "../../../../../db/models/MembershipPayment.php";
require_once "../../../../../alerts/functions.php";


$customer = new Customer();
$membershipPaymentModel = new MembershipPayment();
$membershipPlanModel = new MembershipPlan();

$payments = null;
$membership_titles = null;
$membership_plans = null;

if (!isset($_SESSION['customer']) || !isset($_SESSION['payments']) || !isset($_SESSION['membershipPlans']) || !isset($_SESSION['membership_titles'])) {    
    try {
        $customer->id = $id;
        $customer->get_by_id();
        $payments = $membershipPaymentModel->get_all_of_user($customer->id);
        $membership_titles = $membershipPlanModel->get_all_titles();
        $membership_plans = $membershipPlanModel->get_all();
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch data: " . $e->getMessage(), "/staff/admin/finance/customers");
        exit;
    }
    $_SESSION['customer'] = serialize($customer);
    $_SESSION['payments'] = serialize($payments);
    $_SESSION['membershipPlans'] = serialize($membership_plans);
    $_SESSION['membership_titles'] = serialize($membership_titles);
} else {
    $customer = unserialize($_SESSION['customer']);
    $payments = unserialize($_SESSION['payments']);
    $membership_plans = unserialize($_SESSION['membershipPlans']);
    $membership_titles = unserialize($_SESSION['membership_titles']);
}

$current_payment = null;
$current_plan = null;
if($payment_id){
    foreach ($payments as $payment) {
        if ($payment->id == $payment_id) {
            $current_payment = $payment;
            break;
        }
    }

    if ($current_payment) {
        foreach ($membership_plans as $plan) {
            if ($plan->id == $current_payment->membership_plan) {
                $current_plan = $plan;
                break;
            }
        }
    }
}

$menuBarConfig = [
    "title" => $customer->fname . " " . $customer->lname . " Purchase History",
    "showBack" => true,
    "goBackTo" => ($payment_id) ? "/staff/admin/finance/customers/view/index.php?id=$id" : "/staff/admin/finance/customers/index.php",
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

            <!-- History List Layout -->
            <?php if (!$payment_id) : ?>
                <div>
                    <h1 style="text-align: right; margin-bottom: 20px;">Payment History</h1>
                    <div class="payment-list-item background-color-zinc-100">
                        <div style="grid-column: 1; align-self: center; justify-self: start; text-align: left">
                            Membership Plan
                        </div>
                        <div style="grid-column: 2; align-self: center; justify-self: end; text-align: right">
                            Completed On
                        </div>
                    </div>
                    <?php foreach ($payments as $payment): ?>
                        <a href="/staff/admin/finance/customers/view/index.php?id=<?= $customer->id ?>&payment=<?= $payment->id ?>" 
                        class="payment-list-item background-color-zinc-200">
                            <div style="grid-column: 1; align-self: center; justify-self: start; text-align: left">
                                <?= $membership_titles[$payment->membership_plan] ?? 'Unknown Plan' ?>
                            </div>
                            <div style="grid-column: 2; align-self: center; justify-self: end; text-align: right; font: ">
                                <?= ($payment->completed_at) ? $payment->completed_at->format('Y-m-d') : "Not Completed" ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Individual Payments -->
            <?php if ($payment_id) : ?>
                <div style="margin: 0 10px;">
                    <h1 style="margin-bottom: 30px">Payment No. <?= $current_payment->id ?></h1>
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
                        <p><?= $current_payment->completed_at ? $current_payment->completed_at->format('Y-m-d') : $current_payment->created_at->format('Y-m-d') ?></p>
                    </div>
                    <?php if ($current_payment->completed_at): ?>
                        <div style="margin: 10px 0;">
                            <h1 style="margin-bottom: 5px">Expiry Date</h1>
                            <p><?= $current_payment->completed_at->modify("+{$current_plan->duration} days")->format('Y-m-d') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once "../../../../includes/footer.php"; ?>
