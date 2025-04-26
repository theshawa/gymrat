<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MembershipPayment.php";
require_once "../../../../db/models/MembershipPlan.php";

$pageTitle = "Sales";
$pageStyles = ["../finance.css"];
$sidebarActive = 7;

$get_plan = $_GET['plan'] ?? 0;

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

// validation
if ($get_plan != 0 && !array_key_exists($get_plan, $membership_titles)) {
    $get_plan = 0;
    $_SESSION['error'] = "Invalid membership plan selected. Defaulting to all plans.";
}

// Sort if get_plan given
if ($get_plan != 0) {
    $sales = array_filter($sales, fn($sale) => $sale->membership_plan == $get_plan);
}

$earliest_payment_month_year = null;
$latest_payment_month_year = null;

$completed_dates = array_filter(array_map(fn($sale) => $sale->completed_at, $sales), fn($date) => $date !== null);

if (!empty($completed_dates)) {
    $earliest_payment = min($completed_dates);
    $latest_payment = max($completed_dates);

    $earliest_payment_month_year = $earliest_payment ? $earliest_payment->format('Y-m') : null;
    $latest_payment_month_year = $latest_payment ? $latest_payment->format('Y-m') : null;
}

$record_count = 0;
$total_revenue = 0;

if (!empty($sales)) {
    $completed_sales = array_filter($sales, fn($sale) => $sale->completed_at !== null);
    $record_count = count($completed_sales);
    $total_revenue = array_sum(array_map(fn($sale) => $sale->amount, $completed_sales));
}

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/finance",
    "useLink" => true,
    "options" => [
        ["title" => "Monthly Payments", "href" => "/staff/admin/finance/sales/monthly/index.php", "type" => "secondary"],
        ["title" => "Yearly Payments", "href" => "/staff/admin/finance/sales/yearly/index.php", "type" => "secondary"]
    ]
];

require_once "../../pageconfig.php";
require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 10px;">
            <div style="grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                <h1 style="margin: 10px 0;">Currently displaying : </h1>
                <p>
                    <?= ($get_plan) ? $membership_titles[$get_plan] : "All" ?> Membership Plan Purchases <?=
                     ($earliest_payment_month_year && $latest_payment_month_year) ? "from " . $earliest_payment_month_year . " to " . $latest_payment_month_year : "" ?>
                </p>
            </div>
            <div style="grid-column: 2; align-self: center; justify-self: end; text-align: left;">
                <h3>Records Count&emsp;&nbsp;&nbsp;: <?= $record_count ?></h3>
                <h3>Total Revenue&emsp;&emsp;: Rs. <?= number_format($total_revenue, 2) ?></h3>
            </div>
        </div>


        <div style="margin: 20px 10px;">
            <form method="get" action="/staff/admin/finance/sales/index.php" style="display: flex; gap: 10px; align-items: center; margin: 20px 0;">
                <label for="plan" style="margin-right: 5px;">Plan:</label>
                <select name="plan" id="plan" class="staff-input-primary staff-input-short-alt" required>
                    <option value="0">All Plans</option>
                    <?php foreach ($membership_titles as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $get_plan == $id ? 'selected' : '' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="staff-button secondary" style="margin: 0 10px; height: 40px; border-radius: 10px">Filter</button>
            </form>
        </div>


        <div style="margin: 10px;">
            <div class="payment-list-item background-color-zinc-200" style="display: grid; grid-template-columns: repeat(5, 1fr); font-weight: bold; text-align: center;">
                <div style="text-align: left;">ID</div>
                <div>Customer ID</div>
                <div>Membership Plan</div>
                <div style="text-align: right;" >Amount ( Rs. )</div>
                <div>Completed Date</div>
            </div>
            <?php foreach ($sales as $sale): ?>
                <a href="/staff/admin/finance/sales/view/index.php?id=<?= $sale->id ?>">
                    <div class="payment-list-item background-color-zinc-100" style="display: grid; grid-template-columns: repeat(5, 1fr); text-align: center;">
                        <div style="text-align: left;"><?= htmlspecialchars($sale->id) ?></div>
                        <div><?= htmlspecialchars($sale->customer) ?></div>
                        <div><?= htmlspecialchars($sale->membership_plan) ?></div>
                        <div style="text-align: right;"><?= htmlspecialchars($sale->amount) ?></div>
                        <div><?= htmlspecialchars(($sale->completed_at) ? $sale->completed_at->format('Y-m-d') : "Incomplete") ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
