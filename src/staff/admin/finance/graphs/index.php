<?php
require_once "../../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/MembershipPayment.php";
require_once "../../../../db/models/MembershipPlan.php";


$pageTitle = "Graphs";
$pageStyles = ["../finance.css"];
$pageScripts = [
    "https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js",
    "https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.1.0/dist/chartjs-plugin-annotation.min.js",
    "./membership-chart.js"
];
$sidebarActive = 7;


$current_year = (int)date("Y");
$current_month = (int)date("m");
$get_year = $_GET['year'] ?? 0;
$get_month = $_GET['month'] ?? 0;
$filter = 0;
if ($get_year && $get_month) {
    $filter = 1;
} elseif ($get_year) {
    $filter = 2;
} elseif ($get_month) {
    $filter = 3;
}

if ($get_year > $current_year) {
    $get_year = $current_year;
    $_SESSION['error'] = "Invalid year selected. Defaulting to current year.";
}
if ($get_year == $current_year && $get_month > $current_month) {
    $get_month = $current_month;
    $_SESSION['error'] = "Invalid month selected. Defaulting to current month.";
}


$sales = null;
$membership_titles = null;
$membershipPaymentModel = new MembershipPayment();
$membershipPlanModel = new MembershipPlan();
try {
    switch ($filter) {
        case 0:
            $sales = $membershipPaymentModel->get_all();
            break;
        case 1:
            $sales = $membershipPaymentModel->get_all_sales_for_month($get_year, $get_month);
            break;
        case 2:
            $sales = $membershipPaymentModel->get_all_sales_for_year($get_year);
            break;
        case 3:
            $sales = $membershipPaymentModel->get_all_sales_for_all_month($get_month);
            break;
        default:
            throw new Exception("Invalid filter value");
    }
    $membership_titles = $membershipPlanModel->get_all_titles();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch sales details: " . $e->getMessage(), "/staff/admin/finance");
    exit;
}


$group_sales = [];
foreach ($sales as $sale) {
    if (!empty($sale->completed_at)) {
        $planId = $sale->membership_plan;
        if (!isset($group_sales[$planId])) {
            $group_sales[$planId] = 0;
        }
        $group_sales[$planId]++;
    }
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
       
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 10px;">
            <div style="grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                <h1 style="margin: 10px 0;">Currently displaying : </h1>
                <?php if ($filter == 0): ?>
                <p> All Membership Plan Purchases <?=
                     ($earliest_payment_month_year && $latest_payment_month_year) ? "from " . $earliest_payment_month_year . " to " . $latest_payment_month_year : "" ?>
                </p>
                <?php elseif ($filter == 1): ?>
                    <p> All Membership Plan Purchases for <?= date("F", mktime(0, 0, 0, $get_month, 10)) ?>, <?= $get_year ?></p>
                <?php elseif ($filter == 2): ?>
                    <p> All Membership Plan Purchases for <?= $get_year ?></p>
                <?php elseif ($filter == 3): ?>
                    <p> All Membership Plan Purchases for <?= date("F", mktime(0, 0, 0, $get_month, 10)) ?> in all years</p>
                <?php endif; ?>

            </div>
            <div style="grid-column: 2; align-self: end; justify-self: end; text-align: left;">
                <form method="get" action="/staff/admin/finance/graphs/index.php" style="display: flex; gap: 10px; align-items: center; margin: 20px 0;">
                    <label for="year" style="margin-right: 5px;" >Year:</label>
                    <select name="year" id="year" class="staff-input-primary staff-input-long" required>
                        <option value="0">All Years</option>
                        <?php for ($year = $current_year; $year >= $current_year - 10; $year--): ?>
                            <option value="<?= $year ?>" <?= $year == $get_year ? 'selected' : '' ?>><?= $year ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="month" style="margin-left: 10px; margin-right: 5px;">Month:</label>
                    <select name="month" id="month" class="staff-input-primary staff-input-long" required>
                        <option value="0">All Months</option>
                        <?php for ($month = 1; $month <= 12; $month++): ?>
                            <option value="<?= $month ?>" <?= $month == $get_month ? 'selected' : '' ?>>
                                <?= date("F", mktime(0, 0, 0, $month, 10)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="staff-button secondary" style="margin: 0 10px; height: 40px; border-radius: 10px">Filter</button>
                </form>
            </div>
        </div>

        <div style="width: 80%; height: 80%; margin: 20px auto; text-align: center;">
            <?php if ($group_sales): ?>
                <canvas id="membership-chart"></canvas>
            <?php else: ?>
                <p style="margin: 20px 0;">No data found</p>
            <?php endif; ?>
        </div>
        
    </div>
</main>

<!-- For All membership Plan Chart -->
<script>
    const $GROUPED_SALES = <?= json_encode($group_sales) ?>;
    const $MEMBERSHIP_TITLES = <?= json_encode($membership_titles) ?>;
</script>

<?php
require_once "../../../includes/footer.php";
