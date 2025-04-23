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
require_once "../../db/models/Complaint.php"; 

$complaintModel = new Complaint();
$customerModel = new Customer();

$setComplaintsNotification = null;
$setRatsNotification = null;
try {
    $setComplaintsNotification = $complaintModel->has_unreviewed_complaints();
    $setRatsNotification = $customerModel->has_trainer_unassigned();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
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
                <div class="dashboard-tab-large dashboard-layout-primary">
                    <h1>Welcome Back, Admin!</h1>
                </div>
                <div class="dashboard-tab-large dashboard-layout-primary">
                    <h1>Finance Overview here</h1>
                </div>
            </div>
            <div class="dashboard-col-secondary">
                <a a href="/staff/admin/settings/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Settings</h1>
                </a>
                <div class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Announcments</h1>
                </div>
                <a href="/staff/admin/complaints/index.php" class="dashboard-tab-small dashboard-layout-primary">
                    <div style="grid-row: 1; grid-column: 1; align-self: start; justify-self: start;">
                        <?php if ($setComplaintsNotification): ?>
                            <span class="dashboard-alert-red-dot"></span>
                        <?php endif; ?>
                    </div>
                    <h1>Complaints</h1>
                </a>
                <div class="dashboard-tab-small dashboard-layout-primary">
                    <h1>Staff Credentials</h1>
                </div>
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