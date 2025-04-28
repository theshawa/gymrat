<?php
require_once "../../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/MembershipPayment.php";
require_once "../../../../../db/models/MembershipPlan.php";

$pageTitle = "Yearly Sales";
$pageStyles = ["../../finance.css"];
$sidebarActive = 7;

$current_year = (int)date("Y");
$current_month = (int)date("m");
$get_year = $_GET['year'] ?? (int)date("Y");
$get_plan = $_GET['plan'] ?? 0;



$sales = null;
$membership_titles = null;
$membershipPaymentModel = new MembershipPayment();
$membershipPlanModel = new MembershipPlan();
try {
    $sales = $membershipPaymentModel->get_all_sales_for_year($get_year);
    $membership_titles = $membershipPlanModel->get_all_titles();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch sales details: " . $e->getMessage(), "/staff/admin/finance");
    exit;
}


// validation
if ($get_year > $current_year) {
    $get_year = $current_year;
    $_SESSION['error'] = "Invalid year selected. Defaulting to current year.";
}


if ($get_plan != 0) {
    $sales = array_filter($sales, fn($sale) => $sale->membership_plan == $get_plan);
}

$record_count = 0;
$total_revenue = 0;
$report_description = "";


if (!empty($sales)) {
    $completed_sales = array_filter($sales, fn($sale) => $sale->completed_at !== null);
    $record_count = count($completed_sales);
    $total_revenue = array_sum(array_map(fn($sale) => $sale->amount, $completed_sales));
}

$report_description = (($get_plan) ? $membership_titles[$get_plan] : "All") . " membership plan purchases for " . $get_year;

$_SESSION['sales_data'] = serialize($sales);

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/finance/sales",
    "useLink" => true,
    "options" => [
        ["title" => "All Payments", "href" => "/staff/admin/finance/sales/index.php", "type" => "secondary"],
        ["title" => "Monthly Payments", "href" => "/staff/admin/finance/sales/monthly/index.php", "type" => "secondary"]
    ]
];

require_once "../../../pageconfig.php";
require_once "../../../../includes/header.php";
require_once "../../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../../includes/menubar.php"; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 10px;">
            <div style="grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                <h1 style="margin: 10px 0;">Currently displaying : </h1>
                <p>
                    <?= $report_description ?>
                </p>
            </div>
            <div style="grid-column: 2; align-self: center; justify-self: end; text-align: left;">
                <h3>Records Count&emsp;&nbsp;&nbsp;: <?= $record_count ?></h3>
                <h3>Total Revenue&emsp;&emsp;: Rs. <?= number_format($total_revenue, 2) ?></h3>
            </div>

        </div>

        <div style="margin: 30px 10px; display: grid; grid-template-columns: 3fr 1fr; gap: 20px;">
            <form method="get" action="/staff/admin/finance/sales/yearly/index.php" style="display: flex; gap: 10px; align-items: center; margin: 20px 0;">
                <label for="year" style="margin-right: 5px;" >Year:</label>
                <select name="year" id="year" class="staff-input-primary staff-input-short-alt" required>
                    <?php for ($year = $current_year; $year >= $current_year - 10; $year--): ?>
                        <option value="<?= $year ?>" <?= $year == $get_year ? 'selected' : '' ?>><?= $year ?></option>
                    <?php endfor; ?>
                </select>

                <label for="plan" style="margin-left: 10px; margin-right: 5px;">Plan:</label>
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
            <form method="post" action="/staff/admin/finance/sales/report/index.php"
            target="_blank" style="display: flex; gap: 10px; align-items: center; justify-self: end;">
                <input type="hidden" name="report_title" value="Yearly Membership Plan Sales Report">
                <input type="hidden" name="record_count" value="<?= htmlspecialchars($record_count) ?>">
                <input type="hidden" name="total_revenue" value="<?= htmlspecialchars($total_revenue) ?>">
                <input type="hidden" name="report_description" value="<?= htmlspecialchars($report_description) ?>">
                <?php if ($sales): ?>
                    <button type="submit" class="staff-button secondary" style="margin: 0 10px; height: 40px; border-radius: 10px">Generate Report</button>
                <?php endif; ?>
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

<?php require_once "../../../../includes/footer.php"; ?>
