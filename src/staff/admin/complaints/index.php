<?php

$setFilter = $_GET['filter'] ?? 0;

$pageTitle = "Manage Complaints";
$sidebarActive = 4;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ($setFilter == 1) ? 
        ["title" => "Show All", "href" => "/staff/admin/complaints/index.php", "type" => "primary"] :
        ["title" => "Show Unreviewed", "href" => "/staff/admin/complaints/index.php?filter=1", "type" => "primary"],
    ]
];

require_once "../../../db/models/Complaint.php";

require_once "../../../alerts/functions.php";

$complaints = [];
$complaintModel = new Complaint();
try {
    $complaints = $complaintModel->get_all(-1, $setFilter);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch complaints: " . $e->getMessage(), "/staff/admin");
}

$infoCardConfig = [
    "defaultName" => "Complaint",
    "useListView" => true,
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/admin/complaints/view/index.php",
    "cards" => $complaints
];

require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");
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