<?php
require_once "../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$pageTitle = "Home";
$pageStyles = ["./admin.css"];
$sidebarActive = 1;
$menuBarConfig = [
    "title" => $pageTitle
];


require_once "../../alerts/functions.php";
require_once "../../db/models/Complaint.php"; 
require_once "../../db/models/Customer.php"; 
require_once "../../db/models/MembershipPayment.php";
require_once "../../db/models/Settings.php";


$complaintModel = new Complaint();
$customerModel = new Customer();
$membershipPaymentModel = new MembershipPayment();


$setComplaintsNotification = null;
$setRatsNotification = null;
$settings = new Settings();
try {
    $setComplaintsNotification = $complaintModel->has_unreviewed_complaints();
    $setRatsNotification = $customerModel->has_trainer_unassigned();
    $settings->get_all();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
}


$currentYear = (int)date("Y");
$currentMonth = (int)date("m");
$total_revenues = null;
$total_cevenues = null;
try {
    $total_revenues = $membershipPaymentModel->get_total_revenue_for_month($currentYear, $currentMonth);
    $total_count = $membershipPaymentModel->get_total_count_for_month($currentYear, $currentMonth);
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to calculate revenues: " . $e->getMessage();
}



require_once "./pageconfig.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../includes/menubar.php"; ?>
        

        
        <div class="dashboard-container">
            <div class="dashboard-col-primary">
                <?php if ($settings->gym_banner): ?>
                <div class="dashboard-tab-large alt" 
                style="position: relative; display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center; width: 100%; height: 100%;">
                    <style>
                        .dashboard-tab-large.alt::before {
                            content: "";
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: url('../../uploads/<?= $settings->gym_banner ?>') center center / cover no-repeat;
                            filter: blur(8px) brightness(0.5);
                            z-index: -1;
                        }
                    </style>
                <?php else: ?>
                <div class="dashboard-tab-large" 
                style="display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center;">
                <?php endif; ?>

                    <?php if ($settings->show_widgets): ?>
                        <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; text-align: left;">
                            <h1 class="font-zinc-200" style="font-size: 28px;"><?= $settings->gym_name ?></h1>
                        </div>
                    <?php endif; ?>

                    <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                        <h1 class="font-zinc-200">Welcome Back, Admin!</h1>
                    </div>
                </div>
                <a href="/staff/admin/finance/index.php" class="dashboard-tab-large" 
                style="display: grid; grid-template-rows: 1fr 1fr; grid-template-columns: 1fr 1fr; place-items: center;">

                    <?php if ($settings->show_widgets): ?>
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start; 
                    text-align: left; padding: 25px; border-radius: 20px; box-shadow: 0px 0px 4px rgb(255, 255, 255); width: 75%; height: 90%;"
                    class="background-color-zinc-100">
                        <h1 style="font-size: 36px;"><?=  $total_count ?></h1>
                        <p style="margin: 5px 0;">Membership plans currently <br>activated</p>
                    </div>
                    <div style="grid-row: 2; grid-column: 1; align-self: end; justify-self: start; text-align: left; padding: 10px;">
                        <p class="font-zinc-200" style="margin-bottom: 5px;">Current revenue for the month</p>
                        <h1 class="font-zinc-200" style="font-size: 36px;">Rs. <?=  $total_revenues ?></h1>
                    </div>
                    <?php endif; ?>

                    <div style="grid-row: 2; grid-column: 2; align-self: end; justify-self: end; text-align: right;">
                        <h1 class="font-zinc-200">Finance</h1>
                    </div>
                </a>
            </div>
            <div class="dashboard-col-secondary">
                <a href="/staff/admin/settings/index.php" class="dashboard-tab-small settings dashboard-layout-primary">
                    <h1>Settings</h1>
                </a>
                <a href="/staff/admin/announcements/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Announcments</h1>
                </a>
                <a href="/staff/admin/complaints/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start;">
                        <?php if ($setComplaintsNotification): ?>
                            <span class="dashboard-alert-red-dot"></span>
                        <?php endif; ?>
                    </div>
                    <h1>Complaints</h1>
                </a>
                <a href="/staff/admin/credentials/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Staff Credentials</h1>
                </a>
                <a href="/staff/admin/rats/index.php" class="dashboard-tab-small dashboard-layout-primary">
                <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start;">
                        <?php if ($setRatsNotification): ?>
                            <span class="dashboard-alert-red-dot"></span>
                        <?php endif; ?>
                    </div>
                    <h1>Rats</h1>
                </a>
                <a href="/staff/admin/trainers/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Trainers</h1>
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>