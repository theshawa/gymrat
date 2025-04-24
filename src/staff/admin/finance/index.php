<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../alerts/functions.php";
require_once "../../../db/models/MembershipPayment.php";
require_once "../../../db/models/MembershipPlan.php";


$pageTitle = "Finance";
$pageStyles = ["./finance.css"];
$sidebarActive = 7;
$menuBarConfig = [
    "title" => $pageTitle
];


$membershipPlanModel = new MembershipPlan();
$membershipPaymentModel = new MembershipPayment();
$currentMonthPayments = new MembershipPayment();

$membershipPlans = null;
$currentYear = (int)date("Y");
$currentMonth = (int)date("m");
$total_revenues = [];
$total_counts = [];
try {
    for ($i = 0; $i < 3; $i++) {
        $month = $currentMonth - $i;
        $year = $currentYear;
        if ($month <= 0) {
            $month += 12;
            $year -= 1;
        }
        $total_revenues[] = $membershipPaymentModel->get_total_revenue_for_month($year, $month);
        $total_counts[] = $membershipPaymentModel->get_total_count_for_month($year, $month);
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to calculate revenues: " . $e->getMessage();
}


try {
    $membershipPlans = $membershipPlanModel->get_all();
    $currentMonthPayments = $currentMonthPayments->get_all_sales_grouped_by_plan_for_month($currentYear, $currentMonth);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch sales details: " . $e->getMessage(), "/staff/admin");
    exit;
}


require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div style="display: flex; flex-direction: column">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="overview-small-box" style="grid-column: 1; padding: 20px; display: flex; flex-direction: column; align-items: center;">
                    <h1>Income Overview</h1>
                    <div class="tab">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <?php 
                                $month = $currentMonth - $i;
                                $year = $currentYear;
                                if ($month <= 0) {
                                    $month += 12;
                                    $year -= 1;
                                }
                                $monthName = date("F", mktime(0, 0, 0, $month, 1));
                            ?>
                            <button class="tablinks" onclick="openTab(event, 'Month<?= $i ?>')"><?= $monthName ?> <?= $year ?></button>
                        <?php endfor; ?>
                    </div>

                    <?php for ($i = 0; $i < 3; $i++): ?>
                        <?php 
                            $month = $currentMonth - $i;
                            $year = $currentYear;
                            if ($month <= 0) {
                                $month += 12;
                                $year -= 1;
                            }
                            $monthName = date("F", mktime(0, 0, 0, $month, 1));
                        ?>
                        <div id="Month<?= $i ?>" class="tabcontent">
                            <h3><?= $monthName ?> <?= $year ?></h3>
                            <h1 style="font-size: 52px; margin: 50px">Rs. <?= number_format($total_revenues[$i], 2) ?></h1>
                            <p>Number of membership plans sold: <?= $total_counts[$i] ?></p>
                        </div>
                    <?php endfor; ?>
                </div>
                <div style="grid-column: 2; padding: 10px 20px; 
                display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; gap: 20px;">
                    <a href="/staff/admin/finance/sales" class="finance-link-tab">
                        <h1 class="font-color-zinc-200">Total <br> Sales</h1>
                    </a>
                    <a href="/staff/admin/finance/customers" class="finance-link-tab">
                        <h1 class="font-color-zinc-200">Customers Sales</h1>
                    </a>
                    <a href="" class="finance-link-tab">
                        <h1 class="font-color-zinc-200">Reports</h1>
                    </a>
                    <a href="" class="finance-link-tab">
                        <h1 class="font-color-zinc-200">Visualize</h1>
                    </a>
                </div>
            </div>
            <div class="overview-large-box" style="">
                <div style="padding: 20px; justify-self: center; width:100%; text-align: center;">
                    <h1 >Sales Overview for Current Month</h1>
                </div>
                <?php foreach ($currentMonthPayments as $payment): ?>
                    <?php 
                        $membershipName = "Unknown Plan";
                        foreach ($membershipPlans as $plan) {
                            if ($plan->id == $payment['membership_plan']) {
                                $membershipName = $plan->name;
                                break;
                            }
                        }
                    ?>
                    <div class="overview-list-item">
                        <div style="grid-column: 1; align-self: center;">
                            <h3><?= htmlspecialchars($membershipName) ?></h3>
                        </div>
                        <div style="grid-column: 2; align-self: center; justify-self:center; text-align: center;">
                            <p">Total Count: <strong><?= $payment['total_count'] ?></strong></p>
                        </div>
                        <div style="grid-column: 3; align-self: center; justify-self: end; text-align: right;">
                            <p">Total Amount: <strong>Rs. <?= number_format($payment['total_amount'], 2) ?></strong></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Open the default tab
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".tablinks").click();
    });
</script>