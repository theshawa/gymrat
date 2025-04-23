<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

require_once "../../../alerts/functions.php";
require_once "../../../db/models/MembershipPayment.php";


$pageTitle = "Finance Overview";
$pageStyles = ["./finance.css"];
$sidebarActive = 7;
$menuBarConfig = [
    "title" => $pageTitle
];

$membershipPaymentModel = new MembershipPayment();
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

$currentMonthPayments = new MembershipPayment();
try {
    $currentMonthPayments = $currentMonthPayments->get_all_sales_grouped_by_plan_for_month($currentYear, $currentMonth);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch sales: " . $e->getMessage(), "/staff/admin");
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
                <div style="grid-column: 2; padding: 20px;">
                    <h1>Income Growth Analysis</h1>
                    
                </div>
            </div>
            <div class="overview-large-box">
                
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