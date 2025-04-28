<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$setFilter = $_GET['filter'] ?? 0;
$pageTitle = "Manage Complaints";
$sidebarActive = 6;


require_once "../../../db/models/Complaint.php";
require_once "../../../alerts/functions.php";

$complaints = [];
$complaintModel = new Complaint();
try {
    $complaints = $complaintModel->get_all(-1, $setFilter, 1);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch complaints: " . $e->getMessage(), "/staff/admin");
}

$unreviewed_complaints = null;
try {
    $unreviewed_complaints = $complaintModel->has_unreviewed_complaints();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
}


$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ($setFilter == 1) ? 
        ["title" => "Show All", "href" => "/staff/admin/complaints/index.php", "type" => "primary"] :
        ["title" => "Show Unreviewed", "href" => "/staff/admin/complaints/index.php?filter=1", 
        "type" => "primary", "setAttentionDot" => $unreviewed_complaints],
    ]
];


$infoCardConfig = [
    "defaultName" => "Complaint",
    "useListView" => true,
    "showDescription" => false,
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/admin/complaints/view/index.php",
    "cards" => $complaints
];

require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
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