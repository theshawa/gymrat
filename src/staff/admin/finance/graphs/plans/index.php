<?php
require_once "../../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/MembershipPayment.php";
require_once "../../../../../db/models/MembershipPlan.php";


$pageTitle = "Graphs";
$pageStyles = ["../../finance.css"];
$pageScripts = [
    "https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js",
    "https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.1.0/dist/chartjs-plugin-annotation.min.js",
    "../membership-history-chart.js"
];
$sidebarActive = 7;


$current_year = (int)date("Y");
$current_month = (int)date("m");
$get_year = $_GET['year'] ?? 0;
$get_plan = $_GET['plan'] ?? 0;

if ($get_year > $current_year) {
    $get_year = $current_year;
    $_SESSION['error'] = "Invalid year selected. Defaulting to current year.";
}


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


if ($get_plan != 0 && !array_key_exists($get_plan, $membership_titles)) {
    $get_plan = array_key_first($membership_titles);
    $_SESSION['error'] = "Invalid membership plan selected. Defaulting to a .";
}

if ($get_plan != 0) {
    $sales = array_filter($sales, fn($sale) => $sale->membership_plan == $get_plan);
}


// For displaying date range in info
$earliest_payment_month_year = null;
$latest_payment_month_year = null;

$completed_dates = array_filter(array_map(fn($sale) => $sale->completed_at, $sales), fn($date) => $date !== null);

if (!empty($completed_dates)) {
    $earliest_payment = min($completed_dates);
    $latest_payment = max($completed_dates);

    $earliest_payment_month_year = $earliest_payment ? $earliest_payment->format('Y-m') : null;
    $latest_payment_month_year = $latest_payment ? $latest_payment->format('Y-m') : null;
}

// Prepare data for the line graph
$grouped_sales_by_month = [];
foreach ($sales as $sale) {
    $completed_at = $sale->completed_at;
    if ($completed_at) {
        $year = (int)$completed_at->format('Y');
        $month = (int)$completed_at->format('m');
        if ($get_year == 0 || $year == $get_year) {
            $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            if (!isset($grouped_sales_by_month[$key])) {
                $grouped_sales_by_month[$key] = 0;
            }
            $grouped_sales_by_month[$key]++;
        }
    }
}

// Sort the grouped sales by date
ksort($grouped_sales_by_month);

// Pass data to the frontend
$GROUPED_SALES_BY_MONTH = $grouped_sales_by_month;

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/admin/finance",
    "useLink" => true,
    "options" => [
        ["title" => "All Purchase History", "href" => "/staff/admin/finance/graphs/index.php", "type" => "secondary"],
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
                <?php if ($get_year == 0): ?>
                <p> <?= ($get_plan) ? $membership_titles[$get_plan] : "All" ?> Membership Plan Purchases <?= 
                     ($earliest_payment_month_year && $latest_payment_month_year) ? "from " . $earliest_payment_month_year . " to " . $latest_payment_month_year : "" ?>
                </p>
                <?php else: ?>
                    <p> <?= ($get_plan) ? $membership_titles[$get_plan] : "All" ?> Membership Plan Purchases for <?= $get_year ?></p>
                <?php endif; ?>
            </div>
            <div style="grid-column: 2; align-self: end; justify-self: end; text-align: left;">
                <form method="get" action="/staff/admin/finance/graphs/plans/index.php" style="display: flex; gap: 10px; align-items: center; margin: 20px 0;">
                    <label for="year" style="margin-right: 5px;" >Year:</label>
                    <select name="year" id="year" class="staff-input-primary staff-input-long" required>
                        <option value="0">All Years</option>
                        <?php for ($year = $current_year; $year >= $current_year - 10; $year--): ?>
                            <option value="<?= $year ?>" <?= $year == $get_year ? 'selected' : '' ?>><?= $year ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="plan" style="margin-left: 10px; margin-right: 5px;">Plan:</label>
                    <select name="plan" id="plan" class="staff-input-primary staff-input-long" required>
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
        </div>

        <div style="width: 80%; height: 80%; margin: 20px auto; text-align: center;">
            <?php if ($GROUPED_SALES_BY_MONTH): ?>
                <canvas id="membership-history-chart"></canvas>
            <?php else: ?>
                <p style="margin: 20px 0;">No data found</p>
            <?php endif; ?>
        </div>
        
    </div>
</main>

<!-- Send Data To Membership Plan History Chart -->
<script>
    const $GROUPED_SALES_BY_MONTH = <?= json_encode($GROUPED_SALES_BY_MONTH) ?>;
</script>

<?php
require_once "../../../../includes/footer.php";
