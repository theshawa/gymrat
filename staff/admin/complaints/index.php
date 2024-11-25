<?php

$pageTitle = "Manage Complaints";
$sidebarActive = 4;
$menuBarConfig = [
    "title" => $pageTitle
];

require_once "../../../db/models/Complaint.php";

require_once "../../../alerts/functions.php";

$complaints = [];
$complaintModel = new Complaint();
try {
    $complaints = $complaintModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch complaints: " . $e->getMessage(), "/staff/admin");
}

$infoCardConfig = [
    "defaultName" => "Complaint",
    "showExtend" => true,
    "extendTo" => "/staff/admin/complaints/view/index.php",
    "cards" => $complaints
];

require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard_with_role("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>