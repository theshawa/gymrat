<?php
require_once "../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

require_once "../../db/models/Customer.php";
$customer = new Customer();
$customer->fill([
    'id' => $_SESSION['auth']['id']
]);
try {
    $customer->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get customer: " . $th->getMessage());
}

if (!$customer->membership_plan) {
    die("You don't have a membership plan. Please contact manager to get one.");
}

require_once "../../db/models/MembershipPlan.php";
$plan = new MembershipPlan();
$plan->fill([
    'id' => $customer->membership_plan
]);
try {
    $plan->get_by_id();
} catch (\Throwable $th) {
    die("Failed to get membership plan: " . $th->getMessage());
}

require_once "../../db/models/MembershipPayment.php";
$payment_model = new MembershipPayment();

$payments = [];
try {
    $payments = $payment_model->get_all_of_user($customer->id);
} catch (\Throwable $th) {
    die("Failed to get membership payments: " . $th->getMessage());
}

$pageConfig = [
    "title" => "My Subscription",
    "styles" => ["./subscription.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1,
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";
?>

<main>
    <div class="subscription">
        <h1 class="title"><?= $plan->name ?> Plan</h1>
        <p class="paragraph"><?= $plan->description ?></p>
        <div class="facts">
            <div class="fact">
                <span class="title">
                    Price:
                </span>
                <p class="value"><?= number_format($plan->price, 2) ?></p>
            </div>
            <div class="fact">
                <span class="title">
                    Duration:
                </span>
                <p class="value"><?= $plan->duration ?> days</p>
            </div>
            <?php require_once "../../utils.php" ?>
            <div class="fact">
                <span class="title">
                    Plan activated:
                </span>
                <p class="value"><?= format_time($customer->membership_plan_activated_at) ?></p>
            </div>
            <div class="fact">
                <span class="title">
                    Plan expires:
                </span>
                <?php
                $now = new DateTime();
                $expire_date =      $customer->membership_plan_activated_at;
                $expire_date->modify('+30 days');

                $interval = $now->diff($expire_date);
                $remaining_days = $interval->days;
                ?>
                <p class="value"><?= format_time($expire_date) ?><br /><span class="remaining-days <?= $remaining_days < 8 ? "danger" : "" ?>"><?= $remaining_days ?> days remaining</span></p>
            </div>
        </div>
    </div>
    <div class="payments">
        <h3>Payment History</h3>
        <ul class="payment-list">
            <?php foreach ($payments as $payment): ?>
                <li class="payment">
                    <?php
                    $payed_plan = new MembershipPlan();
                    $payed_plan->fill([
                        'id' => $payment->membership_plan
                    ]);
                    try {
                        $payed_plan->get_by_id();
                    } catch (\Throwable $th) {
                        die("Failed to get membership plan: " . $th->getMessage());
                    }
                    ?>
                    Paid <?= $payment->amount ?> LKR
                    for <?= $payed_plan->name ?> Plan<br />
                    <div class="time"><?= format_time($payment->completed_at) ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</main>